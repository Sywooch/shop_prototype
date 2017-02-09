<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\handlers\AbstractBaseHandler;
use app\services\{GetShortCartWidgetConfigService,
    GetCategoriesMenuWidgetConfigService,
    GetCurrentCurrencyModelService,
    GetCurrencyWidgetConfigService,
    GetEmptyProductsWidgetConfigService,
    GetEmptySphinxWidgetConfigService,
    GetFiltersWidgetConfigSphinxService,
    GetPaginationWidgetConfigSphinxService,
    GetProductsWidgetSphinxConfigService,
    GetSearchWidgetConfigService,
    GetSphinxArrayService,
    GetUserInfoWidgetConfigService,
    ProductsCollectionSphinxService};
use app\finders\{BrandsFilterSphinxFinder,
    CategoriesFinder,
    ColorsFilterSphinxFinder,
    CurrencyFinder,
    ProductsFiltersSessionFinder,
    ProductsSphinxFinder,
    PurchasesSessionFinder,
    SizesFilterSphinxFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SphinxFinder};
use app\helpers\HashHelper;
use app\models\CurrencyInterface;
use app\forms\{FiltersForm,
    ChangeCurrencyForm};
use app\filters\ProductsFiltersInterface;
use app\collections\CollectionInterface;

/**
 * Обрабатывает запрос на получение данных 
 * для рендеринга страницы каталога товаров
 */
class ProductsListSearchRequestHandler extends AbstractBaseHandler
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
            if (empty($request->get(\Yii::$app->params['searchKey']))) {
                throw new ErrorException($this->emptyError('searchKey'));
            }
            
            if (empty($this->dataArray)) {
                $searchText = $request->get(\Yii::$app->params['searchKey']) ?? null;
                if (empty($searchText)) {
                    throw new ErrorException($this->emptyError('searchText'));
                }
                $page = $request->get(\Yii::$app->params['pagePointer']) ?? 0;
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                
                $finder = \Yii::$app->registry->get(ProductsFiltersSessionFinder::class, [
                    'key'=>HashHelper::createFiltersKey(Url::current())
                ]);
                $filtersModel = $finder->find();
                
                $finder = \Yii::$app->registry->get(SphinxFinder::class, [
                    'search'=>$searchText
                ]);
                $sphinxArray = $finder->find();
                $sphinxArray = ArrayHelper::getColumn($sphinxArray, 'id');
                
                $dataArray = [];
                
                /*$service = \Yii::$app->registry->get(GetUserInfoWidgetConfigService::class);
                $dataArray['userInfoWidgetConfig'] = $service->handle();*/
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig();
                
                /*$service = \Yii::$app->registry->get(GetShortCartWidgetConfigService::class);
                $dataArray['shortCartWidgetConfig'] = $service->handle();*/
                $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig($currentCurrencyModel);
                
                /*$service = \Yii::$app->registry->get(GetCurrencyWidgetConfigService::class);
                $dataArray['currencyWidgetConfig'] = $service->handle();*/
                $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currentCurrencyModel);
                
                /*$service = \Yii::$app->registry->get(GetSearchWidgetConfigService::class);
                $dataArray['searchWidgetConfig'] = $service->handle($request);*/
                $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig();
                
                /*$service = \Yii::$app->registry->get(GetCategoriesMenuWidgetConfigService::class);
                $dataArray['categoriesMenuWidgetConfig'] = $service->handle();*/
                $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig();
                
                if (empty($sphinxArray)) {
                    /*$service = \Yii::$app->registry->get(GetEmptySphinxWidgetConfigService::class);
                    $dataArray['emptySphinxWidgetConfig'] = $service->handle();*/
                    $dataArray['emptySphinxWidgetConfig'] = $this->emptySphinxWidgetConfig();
                } else {
                    /*$service = \Yii::$app->registry->get(ProductsCollectionSphinxService::class);
                    $productsCollection = $service->handle($request);*/
                    $finder = \Yii::$app->registry->get(ProductsSphinxFinder::class, [
                        'sphinx'=>$sphinxArray,
                        'page'=>$page,
                        'filters'=>$filtersModel
                    ]);
                    $productsCollection = $finder->find();
                    
                    if ($productsCollection->isEmpty() === true) {
                        if ($productsCollection->pagination->totalCount > 0) {
                            throw new NotFoundHttpException($this->error404());
                        }
                        
                        /*$service = \Yii::$app->registry->get(GetEmptyProductsWidgetConfigService::class);
                        $dataArray['emptyProductsWidgetConfig'] = $service->handle();*/
                        $dataArray['emptyProductsWidgetConfig'] = $this->emptyProductsWidgetConfig();
                    } else {
                        /*$service = \Yii::$app->registry->get(GetProductsWidgetSphinxConfigService::class);
                        $dataArray['productsWidgetConfig'] = $service->handle($request);*/
                        $dataArray['productsWidgetConfig'] = $this->productsWidgetConfig($productsCollection, $currentCurrencyModel);
                        
                        /*$service = \Yii::$app->registry->get(GetPaginationWidgetConfigSphinxService::class);
                        $dataArray['paginationWidgetConfig'] = $service->handle($request);*/
                        $dataArray['paginationWidgetConfig'] = $this->paginationWidgetConfig($productsCollection);
                    }
                    
                    /*$service = \Yii::$app->registry->get(GetFiltersWidgetConfigSphinxService::class);
                    $dataArray['filtersWidgetConfig'] = $service->handle($request);*/
                    $dataArray['filtersWidgetConfig'] = $this->filtersWidgetConfig($sphinxArray, $filtersModel);
                }
                
                /*$service = \Yii::$app->registry->get(GetSearchBreadcrumbsWidgetConfigService::class);
                $dataArray['searchBreadcrumbsWidgetConfig'] = $service->handle($request);*/
                $dataArray['searchBreadcrumbsWidgetConfig'] = $this->searchBreadcrumbsWidgetConfig($searchText);
                
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
     * Возвращает массив конфигурации для виджета EmptySphinxWidget
     * @return array
     */
    private function emptySphinxWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['template'] = 'empty-sphinx.twig';
            
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
     * Возвращает массив конфигурации для виджета FiltersWidget
     * @param array $sphinxArray массив ID найденных записей
     * @param ProductsFiltersInterface $filtersModel
     * @return array
     */
    private function filtersWidgetConfig(array $sphinxArray, ProductsFiltersInterface $filtersModel): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(ColorsFilterSphinxFinder::class, [
                'sphinx'=>$sphinxArray
            ]);
            $colorsArray = $finder->find();
            if (empty($colorsArray)) {
                throw new ErrorException($this->emptyError('colorsArray'));
            }
            ArrayHelper::multisort($colorsArray, 'color');
            $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
            
            $finder = \Yii::$app->registry->get(SizesFilterSphinxFinder::class, [
                'sphinx'=>$sphinxArray
            ]);
            $sizesArray = $finder->find();
            if (empty($sizesArray)) {
                throw new ErrorException($this->emptyError('sizesArray'));
            }
            ArrayHelper::multisort($sizesArray, 'size');
            $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
            
            $finder = \Yii::$app->registry->get(BrandsFilterSphinxFinder::class, [
                'sphinx'=>$sphinxArray
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
            
            $form = new FiltersForm(array_merge(['url'=>Url::current()], array_filter($filtersModel ->toArray())));
            
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
    
    /**
     * Возвращает массив конфигурации для виджета SearchBreadcrumbsWidget
     * @param string $searchText искомая фраза
     * @return array
     */
    private function searchBreadcrumbsWidgetConfig(string $searchText=''): array
    {
        try {
            $dataArray = [];
            
            $dataArray['text'] = $searchText;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
