<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetProductDetailModelService};

/**
 * Возвращает массив конфигурации для виджета ProductBreadcrumbsWidget
 */
class GetProductBreadcrumbsWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета ProductBreadcrumbsWidget
     */
    private $productBreadcrumbsWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета ProductBreadcrumbsWidget
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->productBreadcrumbsWidgetArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetProductDetailModelService::class);
                $dataArray['product'] = $service->handle($request);
                
                $this->productBreadcrumbsWidgetArray = $dataArray;
            }
            
            return $this->productBreadcrumbsWidgetArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
