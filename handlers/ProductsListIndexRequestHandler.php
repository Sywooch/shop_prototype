<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{BrandsFilterFinder,
    CategoriesFinder,
    CategorySeocodeFinder,
    ColorsFilterFinder,
    CurrencyFinder,
    ProductsFinder,
    ProductsFiltersSessionFinder,
    PurchasesSessionFinder,
    SizesFilterFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SubcategorySeocodeFinder};
use app\forms\{AbstractBaseForm,
    ChangeCurrencyForm,
    FiltersForm};
use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;
use app\validators\StripTagsValidator;

/**
 * Обрабатывает запрос на получение данных 
 * для страницы каталога товаров
 */
class ProductsListIndexRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            if (empty($this->dataArray)) {
                $category = $request->get(\Yii::$app->params['categoryKey']) ?? '';
                $subcategory = $request->get(\Yii::$app->params['subcategoryKey']) ?? '';
                $page = $request->get(\Yii::$app->params['pagePointer']) ?? 0;
                
                $validator = new StripTagsValidator();
                $category = $validator->validate($category);
                $subcategory = $validator->validate($subcategory);
                $page = $validator->validate($page);
                
                if (!empty($category)) {
                    if (filter_var($category, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>'#^[a-z0-9-]+$#u']]) === false) {
                        throw new ErrorException($this->invalidError('category'));
                    }
                }
                if (!empty($subcategory)) {
                    if (filter_var($subcategory, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>'#^[a-z0-9-]+$#u']]) === false) {
                        throw new ErrorException($this->invalidError('subcategory'));
                    }
                }
                if (filter_var($page, FILTER_VALIDATE_INT) === false) {
                    throw new ErrorException($this->invalidError('page'));
                }
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                if (empty($currentCurrencyModel)) {
                    throw new ErrorException($this->emptyError('currentCurrencyModel'));
                }
                
                $finder = \Yii::$app->registry->get(ProductsFiltersSessionFinder::class, [
                    'key'=>HashHelper::createFiltersKey(Url::current())
                ]);
                $filtersModel = $finder->find();
                if (empty($filtersModel)) {
                    throw new ErrorException($this->emptyError('filtersModel'));
                }
                
                $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                    'key'=>HashHelper::createCartKey()
                ]);
                $ordersCollection = $finder->find();
                if (empty($ordersCollection)) {
                    throw new ErrorException($this->emptyError('ordersCollection'));
                }
                
                $finder = \Yii::$app->registry->get(ProductsFinder::class, [
                    'category'=>$category,
                    'subcategory'=>$subcategory,
                    'page'=>$page,
                    'filters'=>$filtersModel
                ]);
                $productsCollection = $finder->find();
                
                $finder = \Yii::$app->registry->get(CurrencyFinder::class);
                $currencyArray = $finder->find();
                if (empty($currencyArray)) {
                    throw new ErrorException($this->emptyError('currencyArray'));
                }
                
                $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                $categoriesModelArray = $finder->find();
                if (empty($categoriesModelArray)) {
                    throw new ErrorException($this->emptyError('categoriesModelArray'));
                }
                
                if (!empty($category)) {
                    $finder = \Yii::$app->registry->get(CategorySeocodeFinder::class, [
                        'seocode'=>$category
                    ]);
                    $categoriesModel = $finder->find();
                    if (empty($categoriesModel)) {
                        throw new ErrorException($this->emptyError('categoriesModel'));
                    }
                    if (!empty($subcategory)) {
                        $finder = \Yii::$app->registry->get(SubcategorySeocodeFinder::class, [
                            'seocode'=>$subcategory
                        ]);
                        $subcategoryModel = $finder->find();
                        if (empty($subcategoryModel)) {
                            throw new ErrorException($this->emptyError('subcategoryModel'));
                        }
                    }
                }
                
                $finder = \Yii::$app->registry->get(ColorsFilterFinder::class, [
                    'category'=>$category, 
                    'subcategory'=>$subcategory
                ]);
                $colorsArray = $finder->find();
                if (empty($colorsArray)) {
                    throw new ErrorException($this->emptyError('colorsArray'));
                }
                
                $finder = \Yii::$app->registry->get(SizesFilterFinder::class, [
                    'category'=>$category, 
                    'subcategory'=>$subcategory
                ]);
                $sizesArray = $finder->find();
                if (empty($sizesArray)) {
                    throw new ErrorException($this->emptyError('sizesArray'));
                }
                
                $finder = \Yii::$app->registry->get(BrandsFilterFinder::class, [
                    'category'=>$category, 
                    'subcategory'=>$subcategory
                ]);
                $brandsArray = $finder->find();
                if (empty($brandsArray)) {
                    throw new ErrorException($this->emptyError('brandsArray'));
                }
                
                $finder = \Yii::$app->registry->get(SortingFieldsFinder::class);
                $sortingFieldsArray = $finder->find();
                if (empty($sortingFieldsArray)) {
                    throw new ErrorException($this->emptyError('sortingFieldsArray'));
                }
                
                $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
                $sortingTypesArray = $finder->find();
                if (empty($sortingTypesArray)) {
                    throw new ErrorException($this->emptyError('sortingTypesArray'));
                }
                
                $filtersFormConfig = [
                    'url'=>Url::current(),
                    'category'=>$category,
                    'subcategory'=>$subcategory
                ];
                $filtersForm = new FiltersForm(array_merge($filtersFormConfig, array_filter($filtersModel->toArray())));
                
                $changeCurrencyForm = new ChangeCurrencyForm([
                    'id'=>$currentCurrencyModel->id,
                    'url'=>Url::current()
                ]);
                
                $dataArray = [];
                
                if ($productsCollection->isEmpty() === true) {
                    if ($productsCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                    $dataArray['emptyProductsWidgetConfig'] = $this->emptyProductsWidgetConfig();
                } else {
                    $dataArray['productsWidgetConfig'] = $this->productsWidgetConfig($productsCollection, $currentCurrencyModel);
                    $dataArray['paginationWidgetConfig'] = $this->paginationWidgetConfig($productsCollection->pagination);
                }
                
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig(\Yii::$app->user);
                $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig($ordersCollection, $currentCurrencyModel);
                $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currencyArray, $changeCurrencyForm);
                $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig();
                $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig($categoriesModelArray);
                $dataArray['categoriesBreadcrumbsWidgetConfig'] = $this->categoriesBreadcrumbsWidgetConfig($categoriesModel ?? null, $subcategoryModel ?? null);
                $dataArray['filtersWidgetConfig'] = $this->filtersWidgetConfig($colorsArray, $sizesArray, $brandsArray, $sortingFieldsArray, $sortingTypesArray, $filtersForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CategoriesBreadcrumbsWidget
     * @param mixed $categoriesModel
     * @param mixed $subcategoryModel
     * @return array
     */
    private function categoriesBreadcrumbsWidgetConfig($categoriesModel, $subcategoryModel): array
    {
        try {
            $dataArray = [];
            
            if (!empty($categoriesModel)) {
                $dataArray['category'] = $categoriesModel;
            }
            
            if (!empty($subcategoryModel)) {
                $dataArray['subcategory'] = $subcategoryModel;
            }
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета FiltersWidget
     * @param array $colorsArray
     * @param array $sizesArray
     * @param array $brandsArray
     * @param array $sortingFieldsArray
     * @param array $sortingTypesArray
     * @param AbstractBaseForm $filtersForm
     * @return array
     */
    private function filtersWidgetConfig(array $colorsArray, array $sizesArray, array $brandsArray, array $sortingFieldsArray, array $sortingTypesArray, AbstractBaseForm $filtersForm): array
    {
        try {
            $dataArray = [];
            
            ArrayHelper::multisort($colorsArray, 'color');
            $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
            
            ArrayHelper::multisort($sizesArray, 'size');
            $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
            
            ArrayHelper::multisort($brandsArray, 'brand');
            $dataArray['brands'] = ArrayHelper::map($brandsArray, 'id', 'brand');
            
            asort($sortingFieldsArray, SORT_STRING);
            $dataArray['sortingFields'] = $sortingFieldsArray;
            
            asort($sortingTypesArray, SORT_STRING);
            $dataArray['sortingTypes'] = $sortingTypesArray;
            
            if (empty($filtersForm->sortingField)) {
                foreach ($sortingFieldsArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingField']) {
                        $filtersForm->sortingField = $key;
                    }
                }
            }
            if (empty($filtersForm->sortingType)) {
                foreach ($sortingTypesArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingType']) {
                        $filtersForm->sortingType = $key;
                    }
                }
            }
            
            $dataArray['form'] = $filtersForm;
            $dataArray['header'] = \Yii::t('base', 'Filters');
            $dataArray['template'] = 'products-filters.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
