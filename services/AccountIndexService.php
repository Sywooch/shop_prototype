<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetAccountContactsWidgetConfigService,
    GetAccountMailingsWidgetConfigService,
    GetAccountOrdersWidgetConfigService};

/**
 * Формирует массив данных для рендеринга страницы с настройками аккаунта
 */
class AccountIndexService extends AbstractBaseService
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы с настройками аккаунта
     * @param array $request
     */
    public function handle($request=null)
    {
        try {
            if (empty($this->dataArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetAccountContactsWidgetConfigService::class);
                $dataArray['accountContactsWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetAccountOrdersWidgetConfigService::class);
                $dataArray['accountOrdersWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetAccountMailingsWidgetConfigService::class);
                $dataArray['accountMailingsWidgetConfig'] = $service->handle();
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
