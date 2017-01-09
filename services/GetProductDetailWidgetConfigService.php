<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\services\{GetCurrentCurrencyModelService,
    GetProductDetailModelService};

/**
 * Возвращает массив конфигурации для виджета ProductDetailWidget
 */
class GetProductDetailWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета ProductDetailWidget
     */
    private $productDetailWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета ProductDetailWidget
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->productDetailWidgetArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetProductDetailModelService::class);
                $dataArray['product'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['view'] = 'product-detail.twig';
                
                $this->productDetailWidgetArray = $dataArray;
            }
            
            return $this->productDetailWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
