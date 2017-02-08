<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetShortCartWidgetConfigService,
    GetCategoriesBreadcrumbsWidgetConfigService,
    GetCategoriesMenuWidgetConfigService,
    GetCurrentCurrencyModelService,
    GetCurrencyWidgetConfigService,
    GetEmptyProductsWidgetConfigService,
    GetFiltersWidgetConfigService,
    GetSearchWidgetConfigService,
    GetPaginationWidgetConfigService,
    GetProductsWidgetConfigService,
    GetUserInfoWidgetConfigService,
    ProductsCollectionService};
use app\finders\PurchasesSessionFinder;

/**
 * Обрабатывает запрос на получение данных 
 * для страницы каталога товаров
 */
class ProductsListIndexRequestHandler extends AbstractBaseService
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
                $dataArray = [];
                
                /*$service = \Yii::$app->registry->get(GetUserInfoWidgetConfigService::class);
                $dataArray['userInfoWidgetConfig'] = $service->handle();*/
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig();
                
                /*$service = \Yii::$app->registry->get(GetShortCartWidgetConfigService::class);
                $dataArray['shortCartWidgetConfig'] = $service->handle();*/
                $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig();
                
                $service = \Yii::$app->registry->get(GetCurrencyWidgetConfigService::class);
                $dataArray['currencyWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetSearchWidgetConfigService::class);
                $dataArray['searchWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetCategoriesMenuWidgetConfigService::class);
                $dataArray['categoriesMenuWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(ProductsCollectionService::class);
                $productsCollection = $service->handle($request);
                
                if ($productsCollection->isEmpty() === true) {
                    if ($productsCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                    
                    $service = \Yii::$app->registry->get(GetEmptyProductsWidgetConfigService::class);
                    $dataArray['emptyProductsWidgetConfig'] = $service->handle();
                } else {
                    $service = \Yii::$app->registry->get(GetProductsWidgetConfigService::class);
                    $dataArray['productsWidgetConfig'] = $service->handle($request);
                    
                    $service = \Yii::$app->registry->get(GetPaginationWidgetConfigService::class);
                    $dataArray['paginationWidgetConfig'] = $service->handle($request);
                }
                
                $service = \Yii::$app->registry->get(GetCategoriesBreadcrumbsWidgetConfigService::class);
                $dataArray['categoriesBreadcrumbsWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetFiltersWidgetConfigService::class);
                $dataArray['filtersWidgetConfig'] = $service->handle($request);
                
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
    private function userInfoWidgetConfig()
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
     * @return array
     */
    private function shortCartWidgetConfig()
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                'key'=>HashHelper::createCartKey()
            ]);
            $ordersCollection = $finder->find();
            
            $dataArray['purchases'] = $ordersCollection;
            
            $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                'key'=>HashHelper::createCurrencyKey()
            ]);
            $dataArray['currency'] = $service->get();
            
            $dataArray['template'] = 'short-cart.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CurrencyWidget
     * @return array
     */
    private function currencyWidgetConfig()
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
            
            $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
            $currentCurrencyModel = $service->handle();
            
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
}
