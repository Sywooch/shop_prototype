<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\{NotFoundHttpException,
    Request};
use yii\helpers\{ArrayHelper,
    Url};
use app\services\{AbstractBaseService,
    ChangeCurrencyFormService,
    GetCartWidgetConfigService,
    GetCategoriesMenuWidgetConfigService,
    GetEmptyProductsWidgetConfigService,
    GetSearchWidgetConfigService,
    GetProductsFiltersModelService,
    GetUserInfoWidgetConfigService,
    ProductsListTrait};
use app\finders\{BrandsFilterFinder,
    CategorySeocodeFinder,
    ColorsFilterFinder,
    ProductsFinder,
    SizesFilterFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SubcategorySeocodeFinder};
use app\helpers\HashHelper;
use app\forms\FiltersForm;
use app\collections\ProductsCollection;

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductsListIndexService extends AbstractBaseService
{
    use ProductsListTrait;
    
    /**
     * @var ProductsCollection коллекция товаров
     */
    private $productsCollection = null;
    /**
     * @var array данные для CategoriesBreadcrumbsWidget
     */
    private $breadcrumbsArray = [];
    /**
     * @var array данные для FiltersWidget
     */
    private $filtersArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            $dataArray = [];
            
            $service = new GetUserInfoWidgetConfigService();
            $dataArray['userConfig'] = $service->handle();
            
            $service = new GetCartWidgetConfigService();
            $dataArray['cartConfig'] = $service->handle();
            
            $service = new ChangeCurrencyFormService();
            $dataArray['currencyConfig'] = $service->handle();
            
            $service = new GetSearchWidgetConfigService();
            $dataArray['searchConfig'] = $service->handle($request);
            
            $service = new GetCategoriesMenuWidgetConfigService();
            $dataArray['menuConfig'] = $service->handle();
            
            $productsCollection = $this->getProductsCollection($request);
            
            if ($productsCollection->isEmpty() === true) {
                if ($productsCollection->pagination->totalCount > 0) {
                    throw new NotFoundHttpException($this->error404());
                }
                
                $service = new GetEmptyProductsWidgetConfigService();
                $dataArray['emptyConfig'] = $service->handle();
            } else {
                $dataArray['productsConfig'] = $this->getProductsArray($request);
                $dataArray['paginationConfig'] = $this->getPaginationArray($request);
            }
            
            $dataArray['breadcrumbsConfig'] = $this->getBreadcrumbsArray($request);
            $dataArray['filtersConfig'] = $this->getFiltersArray($request);
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает коллекцию товаров
     * @param Request $request данные запроса
     * @return ProductsCollection
     */
    private function getProductsCollection(Request $request): ProductsCollection
    {
        try {
            if (empty($this->productsCollection)) {
                $service = new GetProductsFiltersModelService();
                $filtersModel = $service->handle();
                
                $finder = \Yii::$app->registry->get(ProductsFinder::class, [
                    'category'=>$request->get(\Yii::$app->params['categoryKey']) ?? null,
                    'subcategory'=>$request->get(\Yii::$app->params['subcategoryKey']) ?? null,
                    'page'=>$request->get(\Yii::$app->params['pagePointer']) ?? 0,
                    'filters'=>$filtersModel
                ]);
                $this->productsCollection = $finder->find();
            }
            
            return $this->productsCollection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CategoriesBreadcrumbsWidget
     * @param Request $request данные запроса
     * @return array
     */
    private function getBreadcrumbsArray(Request $request): array
    {
        try {
            if (empty($this->breadcrumbsArray)) {
                $dataArray = [];
                
                $category = $request->get(\Yii::$app->params['categoryKey']) ?? null;
                $subcategory = $request->get(\Yii::$app->params['subcategoryKey']) ?? null;
                
                if (!empty($category)) {
                    $finder = \Yii::$app->registry->get(CategorySeocodeFinder::class, ['seocode'=>$category]);
                    $categoryModel = $finder->find();
                    if (empty($categoryModel)) {
                        throw new ErrorException($this->emptyError('categoryModel'));
                    }
                    $dataArray['category'] = $categoryModel;
                    
                    if (!empty($subcategory)) {
                        $finder = \Yii::$app->registry->get(SubcategorySeocodeFinder::class, ['seocode'=>$subcategory]);
                        $subcategoryModel = $finder->find();
                        if (empty($subcategoryModel)) {
                            throw new ErrorException($this->emptyError('subcategoryModel'));
                        }
                        $dataArray['subcategory'] = $subcategoryModel;
                    }
                }
                
                $this->breadcrumbsArray = $dataArray;
            }
            
            return $this->breadcrumbsArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета FiltersWidget
     * @param Request $request данные запроса
     * @return array
     */
    private function getFiltersArray(Request $request): array
    {
        try {
            if (empty($this->filtersArray)) {
                $dataArray = [];
                
                $category = $request->get(\Yii::$app->params['categoryKey']) ?? null;
                $subcategory = $request->get(\Yii::$app->params['subcategoryKey']) ?? null;
                
                $finder = \Yii::$app->registry->get(ColorsFilterFinder::class, ['category'=>$category, 'subcategory'=>$subcategory]);
                $colorsArray = $finder->find();
                if (empty($colorsArray)) {
                    throw new ErrorException($this->emptyError('colorsArray'));
                }
                ArrayHelper::multisort($colorsArray, 'color');
                $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
                
                $finder = \Yii::$app->registry->get(SizesFilterFinder::class, ['category'=>$category, 'subcategory'=>$subcategory]);
                $sizesArray = $finder->find();
                if (empty($sizesArray)) {
                    throw new ErrorException($this->emptyError('sizesArray'));
                }
                ArrayHelper::multisort($sizesArray, 'size');
                $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
                
                $finder = \Yii::$app->registry->get(BrandsFilterFinder::class, ['category'=>$category, 'subcategory'=>$subcategory]);
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
                ArrayHelper::multisort($sortingFieldsArray, 'value');
                $dataArray['sortingFields'] = ArrayHelper::map($sortingFieldsArray, 'name', 'value');
                
                $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
                $sortingTypesArray = $finder->find();
                if (empty($sortingTypesArray)) {
                    throw new ErrorException($this->emptyError('sortingTypesArray'));
                }
                ArrayHelper::multisort($sortingTypesArray, 'value');
                $dataArray['sortingTypes'] = ArrayHelper::map($sortingTypesArray, 'name', 'value');
                
                $service = new GetProductsFiltersModelService();
                $filtersModel = $service->handle();
                
                $form = new FiltersForm(array_merge(['url'=>Url::current()], array_filter($filtersModel->toArray())));
                if (empty($form->sortingField)) {
                    foreach ($sortingFieldsArray as $item) {
                        if ($item['name'] === \Yii::$app->params['sortingField']) {
                            $form->sortingField = $item;
                        }
                    }
                }
                if (empty($form->sortingType)) {
                    foreach ($sortingTypesArray as $item) {
                        if ($item['name'] === \Yii::$app->params['sortingType']) {
                            $form->sortingType = $item;
                        }
                    }
                }
                $dataArray['form'] = $form;
                $dataArray['view'] = 'products-filters.twig';
                
                $this->filtersArray = $dataArray;
            }
            
            return $this->filtersArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
