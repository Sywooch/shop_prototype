<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\handlers\{AbstractBaseHandler,
    BaseHandlerTrait,
    BaseFrontendHandlerTrait,
    ProductsListHandlerTrait};
use app\finders\{BrandsFilterFinder,
    CategorySeocodeFinder,
    ColorsFilterFinder,
    ProductsFinder,
    SizesFilterFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SubcategorySeocodeFinder};
use app\forms\FiltersForm;
use app\filters\ProductsFiltersInterface;

/**
 * Обрабатывает запрос на получение данных 
 * для страницы каталога товаров
 */
class ProductsListIndexRequestHandler extends AbstractBaseHandler
{
    use BaseHandlerTrait, BaseFrontendHandlerTrait, ProductsListHandlerTrait;
    
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
                
                $currentCurrencyModel = $this->getCurrentCurrency();
                $filtersModel = $this->getProductsFilters();
                
                $finder = \Yii::$app->registry->get(ProductsFinder::class, [
                    'category'=>$category,
                    'subcategory'=>$subcategory,
                    'page'=>$page,
                    'filters'=>$filtersModel
                ]);
                $productsCollection = $finder->find();
                
                $dataArray = [];
                
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig();
                $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig($currentCurrencyModel);
                $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currentCurrencyModel);
                $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig();
                $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig();
                $dataArray['categoriesBreadcrumbsWidgetConfig'] = $this->categoriesBreadcrumbsWidgetConfig($category, $subcategory);
                $dataArray['filtersWidgetConfig'] = $this->filtersWidgetConfig($category, $subcategory, $filtersModel);
                
                if ($productsCollection->isEmpty() === true) {
                    if ($productsCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                    $dataArray['emptyProductsWidgetConfig'] = $this->emptyProductsWidgetConfig();
                } else {
                    $dataArray['productsWidgetConfig'] = $this->productsWidgetConfig($productsCollection, $currentCurrencyModel);
                    $dataArray['paginationWidgetConfig'] = $this->paginationWidgetConfig($productsCollection);
                }
                
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
     * @param string $category сеокод категории
     * @param string $subcategory сеокод подкатегории
     * @return array
     */
    private function categoriesBreadcrumbsWidgetConfig(string $category='', string $subcategory=''): array
    {
        try {
            $dataArray = [];
            
            if (!empty($category)) {
                $finder = \Yii::$app->registry->get(CategorySeocodeFinder::class, [
                    'seocode'=>$category
                ]);
                $categoryModel = $finder->find();
                if (empty($categoryModel)) {
                    throw new ErrorException($this->emptyError('categoryModel'));
                }
                $dataArray['category'] = $categoryModel;
                
                if (!empty($subcategory)) {
                    $finder = \Yii::$app->registry->get(SubcategorySeocodeFinder::class, [
                        'seocode'=>$subcategory
                    ]);
                    $subcategoryModel = $finder->find();
                    if (empty($subcategoryModel)) {
                        throw new ErrorException($this->emptyError('subcategoryModel'));
                    }
                    $dataArray['subcategory'] = $subcategoryModel;
                }
            }
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета FiltersWidget
     * @param string $category сеокод категории
     * @param string $subcategory сеокод подкатегории
     * @param ProductsFiltersInterface $filtersModel
     * @return array
     */
    private function filtersWidgetConfig(string $category='', string $subcategory='', ProductsFiltersInterface $filtersModel): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(ColorsFilterFinder::class, [
                'category'=>$category, 
                'subcategory'=>$subcategory
            ]);
            $colorsArray = $finder->find();
            if (empty($colorsArray)) {
                throw new ErrorException($this->emptyError('colorsArray'));
            }
            ArrayHelper::multisort($colorsArray, 'color');
            $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
            
            $finder = \Yii::$app->registry->get(SizesFilterFinder::class, [
                'category'=>$category, 
                'subcategory'=>$subcategory
            ]);
            $sizesArray = $finder->find();
            if (empty($sizesArray)) {
                throw new ErrorException($this->emptyError('sizesArray'));
            }
            ArrayHelper::multisort($sizesArray, 'size');
            $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
            
            $finder = \Yii::$app->registry->get(BrandsFilterFinder::class, [
                'category'=>$category, 
                'subcategory'=>$subcategory
            ]);
            $brandsArray = $finder->find();
            if (empty($brandsArray)) {
                throw new ErrorException($this->emptyError('brandsArray'));
            }
            ArrayHelper::multisort($brandsArray, 'brand');
            $dataArray['brands'] = ArrayHelper::map($brandsArray, 'id', 'brand');
            
            $finder = \Yii::$app->registry->get(SortingFieldsFinder::class);
            $sortingFieldsArray = $finder->find();
            if (empty($sortingFieldsArray)) {
                throw new ErrorException($this->emptyError('sortingFieldsArray'));
            }
            asort($sortingFieldsArray, SORT_STRING);
            $dataArray['sortingFields'] = $sortingFieldsArray;
            
            $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
            $sortingTypesArray = $finder->find();
            if (empty($sortingTypesArray)) {
                throw new ErrorException($this->emptyError('sortingTypesArray'));
            }
            asort($sortingTypesArray, SORT_STRING);
            $dataArray['sortingTypes'] = $sortingTypesArray;
            
            $filtersFormConfig = [
                'scenario'=>FiltersForm::SAVE, 
                'url'=>Url::current(),
                'category'=>$category,
                'subcategory'=>$subcategory
            ];
            $form = new FiltersForm(array_merge($filtersFormConfig, array_filter($filtersModel->toArray())));
            
            if (empty($form->sortingField)) {
                foreach ($sortingFieldsArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingField']) {
                        $form->sortingField = $key;
                    }
                }
            }
            
            if (empty($form->sortingType)) {
                foreach ($sortingTypesArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingType']) {
                        $form->sortingType = $key;
                    }
                }
            }
            
            $dataArray['form'] = $form;
            $dataArray['header'] = \Yii::t('base', 'Filters');
            $dataArray['template'] = 'products-filters.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
