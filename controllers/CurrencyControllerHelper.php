<?php

namespace app\controllers;

use app\controllers\AbstractControllerHelper;
use app\exceptions\ExceptionsTrait;
use app\models\CurrencyModel;
use app\helpers\SessionHelper;

/**
 * Коллекция сервис-методов CurrencyController
 */
class CurrencyControllerHelper extends AbstractControllerHelper
{
    /**
     * Пишет в сессию данные выбранной валюты, 
     * делая ее текущей валютой приложения для CurrencyController::actionSet()
     */
    public static function sessionSet()
    {
        try {
            $rawCurrencyModel = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_CHANGE_CURRENCY]);
            
            if ($rawCurrencyModel->load(\Yii::$app->request->post())) {
                if ($rawCurrencyModel ->validate()) {
                    $currencyQuery = CurrencyModel::find();
                    $currencyQuery->extendSelect(['id', 'code', 'exchange_rate']);
                    $currencyQuery->where(['[[currency.id]]'=>$rawCurrencyModel->id]);
                    $currencyQuery->asArray();
                    $currencyArray = $currencyQuery->one();
                    if (is_array($currencyArray) || !empty($currencyArray)) {
                        SessionHelper::write(\Yii::$app->params['currencyKey'], $currencyArray);
                    }
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
