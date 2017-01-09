<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService,
    GetProductsCollectionSphinxService};

/**
 * Возвращает массив данных для ProductsWidget
 */
class GetProductsWidgetSphinxConfigService extends AbstractBaseService
{
    /**
     * @var array данные для ProductsWidget
     */
    private $productsWidgetArray = [];
    
    /**
     * Возвращает массив данных для ProductsWidget
     * @param array $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->productsWidgetArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetProductsCollectionSphinxService::class);
                $dataArray['products'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['view'] = 'products-list.twig';
                
                $this->productsWidgetArray = $dataArray;
            }
            
            return $this->productsWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
