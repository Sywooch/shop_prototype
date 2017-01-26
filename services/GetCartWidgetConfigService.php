<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\PurchasesSessionFinder;
use app\helpers\HashHelper;
use app\services\GetCurrentCurrencyModelService;
use app\forms\PurchaseForm;

/**
 * Возвращает массив конфигурации для виджета ShortCartWidget
 */
class GetCartWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета ShortCartWidget
     */
    private $cartWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета ShortCartWidget
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
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['updateForm'] = new PurchaseForm(['scenario'=>PurchaseForm::UPDATE]);
                $dataArray['deleteForm'] = new PurchaseForm(['scenario'=>PurchaseForm::DELETE]);
                
                $dataArray['header'] = \Yii::t('base', 'Selected products');
                
                $dataArray['template'] = 'cart.twig';
                
                $this->cartWidgetArray = $dataArray;
            }
            
            return $this->cartWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
