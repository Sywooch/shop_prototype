<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\PurchasesSessionFinder;
use app\helpers\HashHelper;
use app\services\GetCurrentCurrencyModelService;

/**
 * Возвращает массив конфигурации для виджета CartWidget
 */
class GetCartWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета CartWidget
     */
    private $cartWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета CartWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->cartWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, ['key'=>HashHelper::createCartKey()]);
                $purchasesCollection = $finder->find();
                
                $dataArray['purchases'] = $purchasesCollection;
                
                $service = new GetCurrentCurrencyModelService();
                $dataArray['currency'] = $service->handle();
                
                $dataArray['view'] = 'short-cart.twig';
                
                $this->cartWidgetArray = $dataArray;
            }
            
            return $this->cartWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
