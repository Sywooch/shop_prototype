<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{CategoriesFinder,
    CategorySeocodeFinder,
    ColorsFilterFinder,
    CurrencyFinder,
    ProductsFinder,
    ProductsFiltersSessionFinder,
    PurchasesSessionFinder,
    SizesFilterFinder,
    ModSortingFieldsFinder,
    SubcategorySeocodeFinder};
use app\forms\{AbstractBaseForm,
    FiltersForm,
    SubscribeForm,
    UserLoginForm};
use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;
use app\validators\StripTagsValidator;

/**
 * Обрабатывает запрос на получение данных 
 * для страницы каталога товаров
 */
class ModProductsListIndexRequestHandler extends AbstractBaseHandler
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
                    $category = filter_var($category, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>'#^[a-z0-9-]+$#u']]);
                    if ($category === false) {
                        throw new ErrorException($this->invalidError('category'));
                    }
                }
                
                if (!empty($subcategory)) {
                    $subcategory = filter_var($subcategory, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>'#^[a-z0-9-]+$#u']]);
                    if ($subcategory === false) {
                        throw new ErrorException($this->invalidError('subcategory'));
                    }
                }
                
                $page = filter_var($page, FILTER_VALIDATE_INT);
                if ($page === false) {
                    throw new ErrorException($this->invalidError('page'));
                }
                
                $finder = \Yii::$app->registry->get(ProductsFiltersSessionFinder::class, [
                    'key'=>HashHelper::createFiltersKey(Url::current())
                ]);
                $filtersModel = $finder->find();
                if (empty($filtersModel)) {
                    throw new ErrorException($this->emptyError('filtersModel'));
                }
                
                $finder = \Yii::$app->registry->get(ProductsFinder::class, [
                    'category'=>$category,
                    'subcategory'=>$subcategory,
                    'page'=>$page,
                    'filters'=>$filtersModel
                ]);
                $productsCollection = $finder->find();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                if (empty($currentCurrencyModel)) {
                    throw new ErrorException($this->emptyError('currentCurrencyModel'));
                }
                
                $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                    'key'=>HashHelper::createCartKey()
                ]);
                $ordersCollection = $finder->find();
                if (empty($ordersCollection)) {
                    throw new ErrorException($this->emptyError('ordersCollection'));
                }
                
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
                
                $finder = \Yii::$app->registry->get(SizesFilterFinder::class, [
                    'category'=>$category, 
                    'subcategory'=>$subcategory
                ]);
                $sizesArray = $finder->find();
                
                $finder = \Yii::$app->registry->get(ModSortingFieldsFinder::class);
                $sortingFieldsArray = $finder->find();
                if (empty($sortingFieldsArray)) {
                    throw new ErrorException($this->emptyError('sortingFieldsArray'));
                }
                
                $filtersFormConfig = [
                    'url'=>Url::current(),
                    'category'=>$category,
                    'subcategory'=>$subcategory
                ];
                $filtersForm = new FiltersForm(array_merge($filtersFormConfig, array_filter($filtersModel->toArray())));
                
                $userLoginForm = new UserLoginForm();
                
                $subscribeForm = new SubscribeForm();
                
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
                
                if (!empty($colorsArray) && !empty($sizesArray)) {
                    $dataArray['filtersWidgetConfig'] = $this->filtersWidgetConfig($colorsArray, $sizesArray, $sortingFieldsArray, $filtersForm);
                }
                
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig(\Yii::$app->user, $userLoginForm);
                $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig($ordersCollection, $currentCurrencyModel);
                $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currencyArray, $currentCurrencyModel);
                $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig();
                $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig($categoriesModelArray);
                $dataArray['categoriesBreadcrumbsWidgetConfig'] = $this->categoriesBreadcrumbsWidgetConfig($categoriesModel ?? null, $subcategoryModel ?? null);
                $dataArray['frontendFooterWidgetConfig'] = $this->frontendFooterWidgetConfig($subscribeForm);
                
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
     * @param array $sortingFieldsArray
     * @param AbstractBaseForm $filtersForm
     * @return array
     */
    private function filtersWidgetConfig(array $colorsArray, array $sizesArray, array $sortingFieldsArray, AbstractBaseForm $filtersForm): array
    {
        try {
            $dataArray = [];
            
            ArrayHelper::multisort($colorsArray, 'color');
            $dataArray['colors'] = $colorsArray;
            
            ArrayHelper::multisort($sizesArray, 'size');
            $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
            
            asort($sortingFieldsArray, SORT_STRING);
            $dataArray['sortingFields'] = $sortingFieldsArray;
            
            if (empty($filtersForm->sortingField)) {
                foreach ($sortingFieldsArray as $key=>$val) {
                    if (explode(' ', $key)[0] === \Yii::$app->params['sortingField']) {
                        $filtersForm->sortingField = $key;
                    }
                }
            } else {
                $type = (int) $filtersForm->sortingType === SORT_ASC ? 'ascending' : 'descending';
                $filtersForm->sortingField = str_replace(['{field}', '{type}'], [$filtersForm->sortingField, $type], '{field} {type}');
            }
            
            $dataArray['form'] = $filtersForm;
            $dataArray['template'] = 'products-filters-mod.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
