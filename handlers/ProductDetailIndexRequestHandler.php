<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\handlers\{AbstractBaseHandler,
    BaseFrontendHandlerTrait};
use app\services\{AbstractBaseService,
    GetShortCartWidgetConfigService,
    GetCategoriesMenuWidgetConfigService,
    GetCommentFormWidgetConfigService,
    GetCommentsWidgetConfigService,
    GetCurrencyWidgetConfigService,
    GetProductBreadcrumbsWidgetConfigService,
    GetProductDetailWidgetConfigService,
    GetPurchaseFormWidgetConfigService,
    GetSearchWidgetConfigService,
    GetSeeAlsoWidgetRelatedConfigService,
    GetSeeAlsoWidgetSimilarConfigService,
    GetUserInfoWidgetConfigService};
use app\models\{CurrencyInterface,
    ProductsModel};
use app\finders\ProductDetailFinder;
use app\forms\PurchaseForm;

/**
 * Обрабатывает запрос на получение данных 
 * для рендеринга страницы каталога товаров
 */
class ProductDetailIndexRequestHandler extends AbstractBaseHandler
{
    use BaseFrontendHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param $request данные запроса
     */
    public function handle($request): array
    {
        try {
            if (empty($this->dataArray)) {
                $seocode = $request->get(\Yii::$app->params['productKey']) ?? null;
                if (empty($seocode)) {
                    throw new ErrorException($this->emptyError('seocode'));
                }
                
                $currentCurrencyModel = $this->getCurrentCurrency();
                
                $finder = \Yii::$app->registry->get(ProductDetailFinder::class, [
                    'seocode'=>$seocode
                ]);
                $productsModel = $finder->find();
                
                if (empty($productsModel)) {
                    throw new NotFoundHttpException($this->error404());
                }
                
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
                
                /*$service = \Yii::$app->registry->get(GetProductDetailWidgetConfigService::class);
                $dataArray['productDetailWidgetConfig'] = $service->handle($request);*/
                $dataArray['productDetailWidgetConfig'] = $this->productDetailWidgetConfig($productsModel, $currentCurrencyModel);
                
                /*$service = \Yii::$app->registry->get(GetPurchaseFormWidgetConfigService::class);
                $dataArray['purchaseFormWidgetConfig'] = $service->handle($request);*/
                $dataArray['purchaseFormWidgetConfig'] = $this->purchaseFormWidgetConfig($productsModel);
                
                /*$service = \Yii::$app->registry->get(GetProductBreadcrumbsWidgetConfigService::class);
                $dataArray['productBreadcrumbsWidget'] = $service->handle($request);*/
                $dataArray['productBreadcrumbsWidget'] = $this->productBreadcrumbsWidget($productsModel);
                
                $service = \Yii::$app->registry->get(GetSeeAlsoWidgetSimilarConfigService::class);
                $dataArray['seeAlsoWidgetSimilarConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetSeeAlsoWidgetRelatedConfigService::class);
                $dataArray['seeAlsoWidgetRelatedConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetCommentsWidgetConfigService::class);
                $dataArray['commentsWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetCommentFormWidgetConfigService::class);
                $dataArray['сommentFormWidgetConfig'] = $service->handle($request);
                
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
     * Возвращает массив конфигурации для виджета ProductDetailWidget
     * @param ProductsModel $productsModel объект товара
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function productDetailWidgetConfig(ProductsModel $productsModel, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['product'] = $productsModel;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'product-detail.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PurchaseFormWidget
     * @param ProductsModel $productsModel объект товара
     * @return array
     */
    private function purchaseFormWidgetConfig(ProductsModel $productsModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['product'] = $productsModel;
            $dataArray['form'] = new PurchaseForm(['scenario'=>PurchaseForm::SAVE, 'quantity'=>1]);
            $dataArray['template'] = 'purchase-form.twig';
            
            $this->purchaseFormWidgetArray = $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ProductBreadcrumbsWidget
     * @param ProductsModel $productsModel объект товара
     * @return array
     */
    private function productBreadcrumbsWidget(ProductsModel $productsModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['product'] = $productsModel;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
