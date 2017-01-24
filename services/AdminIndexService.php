<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetAdminOrdersWidgetConfigService,
    GetAverageBillWidgetConfigService};

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
                
                $service = \Yii::$app->registry->get(GetAdminOrdersWidgetConfigService::class);
                $dataArray['adminOrdersWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetAverageBillWidgetConfigService::class);
                $dataArray['averageBillWidgetConfig'] = $service->handle();
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
