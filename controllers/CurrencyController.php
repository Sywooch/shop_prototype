<?php

namespace app\controllers;

use yii\web\Controller;
use yii\helpers\Url;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use app\models\CurrencyModel;
use app\helpers\MappersHelper;
use app\helpers\RedirectHelper;

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
            
            if (\Yii::$app->request->isPost && $currencyModel->load(\Yii::$app->request->post())) {
                if ($currencyModel->validate()) {
                    if (!empty(\Yii::$app->user)) {
                        \Yii::$app->user->currency = MappersHelper::getCurrencyModelById($currencyModel);
                    }
                    $urlArray = RedirectHelper::getRedirectUrl();
                    if (!is_array($urlArray) || empty($urlArray)) {
                        throw new ErrorException('Ошибка при получении данных для редиректа!');
                    }
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
            return $this->redirect(Url::to($urlArray));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
