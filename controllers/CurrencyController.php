<?php

namespace app\controllers;

use yii\web\Controller;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use app\models\CurrencyModel;

/**
 * Обрабатывает запросы, связанные с валютами сайта
 */
class CurrencyController extends Controller
{
    use ExceptionsTrait;
    
    /**
     * Обрабатывает запрос на установку валюты
     */
    public function actionSetCurrency()
    {
        try {
            $currencyModel = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_FORM_SET]);
            
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
