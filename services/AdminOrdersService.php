<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetAdminOrdersFormWidgetConfigService};

/**
 * Формирует массив данных для рендеринга страницы 
 * с перечнем заказов
 */
class AdminOrdersService extends AbstractBaseService
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
                
                $service = \Yii::$app->registry->get(GetAdminOrdersFormWidgetConfigService::class);
                $dataArray['adminOrdersFormWidgetConfig'] = $service->handle();
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
