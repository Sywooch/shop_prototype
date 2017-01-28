<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\PurchasesTodayFinder;

/**
 * Возвращает массив конфигурации для виджета AdminTodayOrdersMinimalWidget
 */
class GetAdminTodayOrdersMinimalWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AdminTodayOrdersMinimalWidget
     */
    private $adminTodayOrdersMinimalWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->adminTodayOrdersMinimalWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Orders');
                
                $finder = \Yii::$app->registry->get(PurchasesTodayFinder::class);
                $purchases = $finder->find();
                $dataArray['purchases'] = $purchases->count();
                
                $dataArray['template'] = 'admin-today-orders-minimal.twig';
                
                $this->adminTodayOrdersMinimalWidgetArray = $dataArray;
            }
            
            return $this->adminTodayOrdersMinimalWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
