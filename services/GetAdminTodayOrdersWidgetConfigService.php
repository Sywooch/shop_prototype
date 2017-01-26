<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService};
use app\finders\PurchasesTodayFinder;

/**
 * Возвращает массив конфигурации для виджета AdminTodayOrdersWidget
 */
class GetAdminTodayOrdersWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AdminTodayOrdersWidget
     */
    private $adminTodayOrdersWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->adminTodayOrdersWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Orders received today');
                
                $finder = \Yii::$app->registry->get(PurchasesTodayFinder::class);
                $purchases = $finder->find()->asArray();
                if (!empty($purchases)) {
                    ArrayHelper::multisort($purchases, 'received_date', SORT_DESC, SORT_NUMERIC);
                    $purchases = array_slice($purchases, 0, \Yii::$app->params['todayOrdersLimit']);
                }
                $dataArray['purchases'] = $purchases;
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['template'] = 'admin-purchases.twig';
                
                $this->adminTodayOrdersWidgetArray = $dataArray;
            }
            
            return $this->adminTodayOrdersWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
