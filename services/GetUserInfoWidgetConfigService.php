<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает объект текущей валюты
 */
class GetUserInfoWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета UserInfoWidget
     */
    private $userInfoWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета UserInfoWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->userInfoWidgetArray)) {
                $dataArray = [];
                
                $dataArray['user'] = \Yii::$app->user;
                $dataArray['template'] = 'user-info.twig';
                
                $this->userInfoWidgetArray = $dataArray;
            }
            
            return $this->userInfoWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
