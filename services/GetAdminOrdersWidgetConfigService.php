<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService};
use app\finders\PurchasesTodayFinder;

/**
 * Возвращает массив конфигурации для виджета AdminOrdersWidget
 */
class GetAdminOrdersWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AdminOrdersWidget
     */
    private $adminOrdersWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->adminOrdersWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Orders received today');
                
                $finder = \Yii::$app->registry->get(PurchasesTodayFinder::class);
                $dataArray['purchases'] = $finder->find()->asArray();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['view'] = 'admin-purchases.twig';
                
                $this->adminOrdersWidgetArray = $dataArray;
            }
            
            return $this->adminOrdersWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
