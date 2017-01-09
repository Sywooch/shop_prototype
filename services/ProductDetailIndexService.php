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
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetUserInfoWidgetConfigService::class);
                $dataArray['userInfoWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetCartWidgetConfigService::class);
                $dataArray['cartWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetCurrencyWidgetConfigService::class);
                $dataArray['currencyWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetSearchWidgetConfigService::class);
                $dataArray['searchWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetCategoriesMenuWidgetConfigService::class);
                $dataArray['categoriesMenuWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetProductDetailWidgetConfigService::class);
                $dataArray['productDetailWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetPurchaseFormWidgetConfigService::class);
                $dataArray['purchaseFormWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetProductBreadcrumbsWidgetConfigService::class);
                $dataArray['productBreadcrumbsWidget'] = $service->handle($request);
                
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
}
