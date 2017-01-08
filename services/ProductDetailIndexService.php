<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetCartWidgetConfigService,
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

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductDetailIndexService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param $request данные запроса
     */
    public function handle($request): array
    {
        try {
            $dataArray = [];
            
            $service = new GetUserInfoWidgetConfigService();
            $dataArray['userInfoWidgetConfig'] = $service->handle();
            
            $service = new GetCartWidgetConfigService();
            $dataArray['cartWidgetConfig'] = $service->handle();
            
            $service = new GetCurrencyWidgetConfigService();
            $dataArray['currencyWidgetConfig'] = $service->handle();
            
            $service = new GetSearchWidgetConfigService();
            $dataArray['searchWidgetConfig'] = $service->handle($request);
            
            $service = new GetCategoriesMenuWidgetConfigService();
            $dataArray['categoriesMenuWidgetConfig'] = $service->handle();
            
            $service = new GetProductDetailWidgetConfigService();
            $dataArray['productDetailWidgetConfig'] = $service->handle($request);
            
            $service = new GetPurchaseFormWidgetConfigService();
            $dataArray['purchaseFormWidgetConfig'] = $service->handle($request);
            
            $service = new GetProductBreadcrumbsWidgetConfigService();
            $dataArray['productBreadcrumbsWidget'] = $service->handle($request);
            
            $service = new GetSeeAlsoWidgetSimilarConfigService();
            $dataArray['seeAlsoWidgetSimilarConfig'] = $service->handle($request);
            
            $service = new GetSeeAlsoWidgetRelatedConfigService();
            $dataArray['seeAlsoWidgetRelatedConfig'] = $service->handle($request);
            
            $service = new GetCommentsWidgetConfigService();
            $dataArray['commentsWidgetConfig'] = $service->handle($request);
            
            $service = new GetCommentFormWidgetConfigService();
            $dataArray['сommentFormWidgetConfig'] = $service->handle($request);
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
