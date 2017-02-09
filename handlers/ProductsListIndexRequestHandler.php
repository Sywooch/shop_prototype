<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\handlers\AbstractBaseHandler;
use app\services\GetCurrentCurrencyModelService;
use app\finders\{BrandsFilterFinder,
    CategoriesFinder,
    CategorySeocodeFinder,
    ColorsFilterFinder,
    CurrencyFinder,
    ProductsFiltersSessionFinder,
    ProductsFinder,
    PurchasesSessionFinder,
    SizesFilterFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SubcategorySeocodeFinder};
use app\forms\{ChangeCurrencyForm,
    FiltersForm};
use app\models\CurrencyInterface;
use app\helpers\HashHelper;
use app\filters\ProductsFiltersInterface;
use app\collections\CollectionInterface;

/**
 * Обрабатывает запрос на получение данных 
 * для страницы каталога товаров
 */
class ProductsListIndexRequestHandler extends AbstractBaseHandler
{
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
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                
                $finder = \Yii::$app->registry->get(ProductsFiltersSessionFinder::class, [
                    'key'=>HashHelper::createFiltersKey(Url::current())
                ]);
                $filtersModel = $finder->find();
                
                $dataArray = [];
                
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig();
                $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig($currentCurrencyModel);
                $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currentCurrencyModel);
                $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig();
                $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig();
                
                $finder = \Yii::$app->registry->get(ProductsFinder::class, [
                    'category'=>$category,
                    'subcategory'=>$subcategory,
                    'page'=>$page,
                    'filters'=>$filtersModel
                ]);
                $productsCollection = $finder->find();
                
                if ($productsCollection->isEmpty() === true) {
                    if ($productsCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                    $dataArray['emptyProductsWidgetConfig'] = $this->emptyProductsWidgetConfig();
                } else {
                    $dataArray['productsWidgetConfig'] = $this->productsWidgetConfig($productsCollection, $currentCurrencyModel);
                    $dataArray['paginationWidgetConfig'] = $this->paginationWidgetConfig($productsCollection);
                }
                
                $dataArray['categoriesBreadcrumbsWidgetConfig'] = $this->categoriesBreadcrumbsWidgetConfig($category, $subcategory);
                $dataArray['filtersWidgetConfig'] = $this->filtersWidgetConfig($category, $subcategory, $filtersModel);
                
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
     * Возвращает массив конфигурации для виджета UserInfoWidget
     * @return array
     */
    private function userInfoWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['user'] = \Yii::$app->user;
            $dataArray['template'] = 'user-info.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ShortCartWidget
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function shortCartWidgetConfig(CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                'key'=>HashHelper::createCartKey()
            ]);
            $ordersCollection = $finder->find();
            
            $dataArray['purchases'] = $ordersCollection;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'short-cart.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CurrencyWidget
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function currencyWidgetConfig(CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(CurrencyFinder::class);
            $currencyArray = $finder->find();
            if (empty($currencyArray)) {
                throw new ErrorException($this->emptyError('currencyArray'));
            }
            ArrayHelper::multisort($currencyArray, 'code');
            $dataArray['currency'] = ArrayHelper::map($currencyArray, 'id', 'code');
            
            $dataArray['form'] = new ChangeCurrencyForm([
                'scenario'=>ChangeCurrencyForm::SET,
                'id'=>$currentCurrencyModel->id,
                'url'=>Url::current()
            ]);
            
            $dataArray['header'] = \Yii::t('base', 'Currency');
            $dataArray['template'] = 'currency-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета SearchWidget
     * @param string $searchKey искомая фраза
     * @return array
     */
    private function searchWidgetConfig(string $searchKey=''): array
    {
        try {
            $dataArray = [];
            
            $dataArray['text'] = $searchKey;
            $dataArray['template'] = 'search.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CategoriesMenuWidget
     * @return array
     */
    private function categoriesMenuWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(CategoriesFinder::class);
            $categoriesArray = $finder->find();
            if (empty($categoriesArray)) {
                throw new ErrorException($this->emptyError('categoriesArray'));
            }
            $dataArray['categories'] = $categoriesArray;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета EmptyProductsWidget
     * @return array
     */
    private function emptyProductsWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['template'] = 'empty-products.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ProductsWidget
     * @param CollectionInterface $productsCollection
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function productsWidgetConfig(CollectionInterface $productsCollection, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['products'] = $productsCollection;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'products-list.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PaginationWidget
     * @param CollectionInterface $productsCollection
     * @return array
     */
    private function paginationWidgetConfig(CollectionInterface $productsCollection): array
    {
        try {
            $dataArray = [];
            
            $pagination = $productsCollection->pagination;
            
            if (empty($pagination)) {
                throw new ErrorException($this->emptyError('pagination'));
            }
            
            $dataArray['pagination'] = $pagination;
            $dataArray['template'] = 'pagination.twig';
            
            return $dataArray;
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
