<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\services\{AbstractBaseService,
    FrontendTrait};
use app\finders\{BrandsFilterFinder,
    CategorySeocodeFinder,
    ColorsFilterFinder,
    FiltersSessionFinder,
    ProductsFinder,
    SizesFilterFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SubcategorySeocodeFinder};
use app\helpers\HashHelper;
use app\forms\FiltersForm;
use app\filters\ProductsFilters;
use app\collections\ProductsCollection;

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductsListIndexService extends AbstractBaseService
{
    use FrontendTrait;
    
    /**
     * @var ProductsFilters объект текущих фильтров
     */
    private $filtersModel = null;
    /**
     * @var ProductsCollection коллекция товаров
     */
    private $productsCollection = null;
    /**
     * @var array данные для EmptyProductsWidget
     */
    private $emptyProductsArray = [];
    /**
     * @var array данные для ProductsWidget
     */
    private $productsArray = [];
    /**
     * @var array данные для PaginationWidget
     */
    private $paginationArray = [];
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
            
            $dataArray['userConfig'] = $this->getUserArray();
            $dataArray['cartConfig'] = $this->getCartArray();
            $dataArray['currencyConfig'] = $this->getCurrencyArray();
            $dataArray['searchConfig'] = $this->getSearchArray();
            $dataArray['menuConfig'] = $this->getCategoriesArray();
            
            $productsCollection = $this->getProductsCollection($request);
            if ($productsCollection->isEmpty() === true) {
                if ($productsCollection->pagination->totalCount > 0) {
                    throw new NotFoundHttpException($this->error404());
                }
                $dataArray['emptyConfig'] = $this->getEmptyProductsArray();
            } else {
                $dataArray['productsConfig'] = $this->getProductsArray($request);
            }
            
            $dataArray['paginationConfig'] = $this->getPaginationArray($request);
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
     * Возвращает модель товарных фильтров
     * @return ProductsFilters
     */
    private function getFiltersModel(): ProductsFilters
    {
        try {
            if (empty($this->filtersModel)) {
                $finder = new FiltersSessionFinder([
                    'key'=>HashHelper::createFiltersKey(Url::current())
                 ]);
                $filtersModel = $finder->find();
                
                if (empty($filtersModel)) {
                    throw new ErrorException($this->emptyError('filtersModel'));
                }
                
                $this->filtersModel = $filtersModel;
            }
            
            return $this->filtersModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает коллекцию товаров
     * @param array $request массив данных запроса
     * @return ProductsCollection
     */
    private function getProductsCollection(array $request): ProductsCollection
    {
        try {
            if (empty($this->productsCollection)) {
                $finder = new ProductsFinder([
                    'category'=>$request[\Yii::$app->params['categoryKey']] ?? null,
                    'subcategory'=>$request[\Yii::$app->params['subcategoryKey']] ?? null,
                    'page'=>$request[\Yii::$app->params['pagePointer']] ?? 0,
                    'filters'=>$this->getFiltersModel()
                ]);
                $this->productsCollection = $finder->find();
            }
            
            return $this->productsCollection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета EmptyProductsWidget
     * @return array
     */
    private function getEmptyProductsArray(): array
    {
        try {
            if (empty($this->productsArray)) {
                $dataArray = [];
                
                $dataArray['view'] = 'empty-products.twig';
                
                $this->productsArray = $dataArray;
            }
            
            return $this->productsArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ProductsWidget
     * @param array $request массив данных запроса
     * @return array
     */
    private function getProductsArray(array $request): array
    {
        try {
            if (empty($this->productsArray)) {
                $dataArray = [];
                
                $dataArray['products'] = $this->getProductsCollection($request);
                $dataArray['currency'] = $this->getCurrencyModel();
                $dataArray['view'] = 'products-list.twig';
                
                $this->productsArray = $dataArray;
            }
            
            return $this->productsArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PaginationWidget
     * @param array $request массив данных запроса
     * @return array
     */
    private function getPaginationArray(array $request): array
    {
        try {
            if (empty($this->paginationArray)) {
                $dataArray = [];
                
                $pagination = $this->getProductsCollection($request)->pagination;
                
                if (empty($pagination)) {
                    throw new ErrorException($this->emptyError('pagination'));
                }
                
                $dataArray['pagination'] = $pagination;
                $dataArray['view'] = 'pagination.twig';
                
                $this->paginationArray = $dataArray;
            }
            
            return $this->paginationArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CategoriesBreadcrumbsWidget
     * @param array $request массив данных запроса
     * @return array
     */
    private function getBreadcrumbsArray(array $request): array
    {
        try {
            if (empty($this->breadcrumbsArray)) {
                $dataArray = [];
                
                $category = $request[\Yii::$app->params['categoryKey']] ?? null;
                $subcategory = $request[\Yii::$app->params['subcategoryKey']] ?? null;
                
                if (!empty($category)) {
                    $finder = new CategorySeocodeFinder([
                        'seocode'=>$category
                    ]);
                    $categoryModel = $finder->find();
                    if (empty($categoryModel)) {
                        throw new ErrorException($this->emptyError('categoryModel'));
                    }
                    $dataArray['category'] = $categoryModel;
                    
                    if (!empty($subcategory)) {
                        $finder = new SubcategorySeocodeFinder([
                            'seocode'=>$subcategory
                        ]);
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
     * @param array $request массив данных запроса 
     * @return array
     */
    private function getFiltersArray(array $request): array
    {
        try {
            if (empty($this->filtersArray)) {
                $dataArray = [];
                
                $category = $request[\Yii::$app->params['categoryKey']] ?? null;
                $subcategory = $request[\Yii::$app->params['subcategoryKey']] ?? null;
                
                $finder = new ColorsFilterFinder([
                    'category'=>$category,
                    'subcategory'=>$subcategory,
                ]);
                $colorsArray = $finder->find();
                if (empty($colorsArray)) {
                    throw new ErrorException($this->emptyError('colorsArray'));
                }
                ArrayHelper::multisort($colorsArray, 'color');
                $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
                
                $finder = new SizesFilterFinder([
                    'category'=>$category,
                    'subcategory'=>$subcategory,
                ]);
                $sizesArray = $finder->find();
                if (empty($sizesArray)) {
                    throw new ErrorException($this->emptyError('sizesArray'));
                }
                ArrayHelper::multisort($sizesArray, 'size');
                $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
                
                $finder = new BrandsFilterFinder([
                    'category'=>$category,
                    'subcategory'=>$subcategory,
                ]);
                $brandsArray = $finder->find();
                if (empty($brandsArray)) {
                    throw new ErrorException($this->emptyError('brandsArray'));
                }
                ArrayHelper::multisort($brandsArray, 'brand');
                $dataArray['brands'] = ArrayHelper::map($brandsArray, 'id', 'brand');
                
                $finder = new SortingFieldsFinder();
                $sortingFieldsArray = $finder->find();
                if (empty($sortingFieldsArray)) {
                    throw new ErrorException($this->emptyError('sortingFieldsArray'));
                }
                ArrayHelper::multisort($sortingFieldsArray, 'value');
                $dataArray['sortingFields'] = ArrayHelper::map($sortingFieldsArray, 'name', 'value');
                
                $finder = new SortingTypesFinder();
                $sortingTypesArray = $finder->find();
                if (empty($sortingTypesArray)) {
                    throw new ErrorException($this->emptyError('sortingTypesArray'));
                }
                ArrayHelper::multisort($sortingTypesArray, 'value');
                $dataArray['sortingTypes'] = ArrayHelper::map($sortingTypesArray, 'name', 'value');
                
                $form = new FiltersForm(array_merge(['url'=>Url::current()], array_filter($this->getFiltersModel()->toArray())));
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
