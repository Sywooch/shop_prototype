<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetAdminTodayOrdersWidgetConfigService,
    GetAdminTodayOrdersMinimalWidgetConfigService,
    GetAverageBillWidgetConfigService,
    GetConversionWidgetConfigService,
    GetPopularGoodsWidgetConfigService,
    GetVisitsMinimalWidgetConfigService,
    GetVisitsWidgetConfigService};

/**
 * Формирует массив данных для рендеринга страницы 
 * с основными данными админ раздела
 */
class AdminIndexService extends AbstractBaseService
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param array $request
     */
    public function handle($request=null)
    {
        try {
            if (empty($this->dataArray)) {
                $dataArray = [1];
                
                /*$service = \Yii::$app->registry->get(GetAdminTodayOrdersWidgetConfigService::class);
                $dataArray['adminTodayOrdersWidgetConfig'] = $service->handle();*/
                
                $service = \Yii::$app->registry->get(GetAdminTodayOrdersMinimalWidgetConfigService::class);
                $dataArray['adminTodayOrdersMinimalWidgetConfig'] = $service->handle();
                
                /*$service = \Yii::$app->registry->get(GetVisitsWidgetConfigService::class);
                $dataArray['visitsWidgetConfig'] = $service->handle();*/
                
                $service = \Yii::$app->registry->get(GetVisitsMinimalWidgetConfigService::class);
                $dataArray['visitsMinimalWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetConversionWidgetConfigService::class);
                $dataArray['conversionWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetAverageBillWidgetConfigService::class);
                $dataArray['averageBillWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetPopularGoodsWidgetConfigService::class);
                $dataArray['popularGoodsWidgetConfig'] = $service->handle();
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
