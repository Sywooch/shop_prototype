<?php

namespace app\handlers;

use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;
use app\models\CurrencyInterface;

/**
 * Коллекция базовых методов
 */
trait BaseHandlerTrait
{
    /**
     * Возвращает CurrencyInterface объект текущей валюты
     * @return CurrencyInterface
     */
    protected function getCurrentCurrency(): CurrencyInterface
    {
        try {
            $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                'key'=>HashHelper::createCurrencyKey()
            ]);
            $currentCurrencyModel = $service->get();
            
            if (empty($currentCurrencyModel)) {
                throw new ErrorException($this->emptyError('currentCurrencyModel'));
            }
            
            return $currentCurrencyModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UserInfoWidget
     * @return array
     */
    protected function userInfoWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['user'] = \Yii::$app->user;
            $dataArray['template'] = 'user-info.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
