<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetAccountMailingsFormWidgetConfigService,
    GetAccountMailingsUnsubscribeWidgetConfigService};

/**
 * Формирует массив данных для рендеринга страницы с данными о подписках
 */
class AccountChangeSubscriptionsService extends AbstractBaseService
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
                
                $service = \Yii::$app->registry->get(GetAccountMailingsUnsubscribeWidgetConfigService::class);
                $dataArray['accountMailingsUnsubscribeWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetAccountMailingsFormWidgetConfigService::class);
                $dataArray['accountMailingsFormWidgetConfig'] = $service->handle();
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
