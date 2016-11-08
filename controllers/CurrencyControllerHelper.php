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
    public static function setPost()
    {
        try {
            $rawCurrencyModel = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_CHANGE_CURRENCY]);
            
            if ($rawCurrencyModel->load(\Yii::$app->request->post())) {
                if ($rawCurrencyModel ->validate()) {
                    if (!empty($currencyModel = self::getCurrency($rawCurrencyModel['id'], true))) {
                        SessionHelper::write(\Yii::$app->params['currencyKey'], $currencyModel);
                    }
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
