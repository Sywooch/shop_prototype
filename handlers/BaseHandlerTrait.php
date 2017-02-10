<?php

namespace app\handlers;

use yii\base\ErrorException;
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
    private function getCurrentCurrency(): CurrencyInterface
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
}
