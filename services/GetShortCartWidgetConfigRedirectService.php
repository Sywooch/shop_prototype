<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\PurchasesSessionFinder;
use app\helpers\HashHelper;
use app\services\GetCurrentCurrencyModelService;

/**
 * Возвращает массив конфигурации для виджета ShortCartWidget
 */
class GetShortCartWidgetConfigRedirectService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета ShortCartWidget
     */
    private $shortCartWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета ShortCartWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->shortCartWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, ['key'=>HashHelper::createCartKey()]);
                $purchasesCollection = $finder->find();
                
                $dataArray['purchases'] = $purchasesCollection;
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['view'] = 'short-cart-redirect.twig';
                
                $this->shortCartWidgetArray = $dataArray;
            }
            
            return $this->shortCartWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
