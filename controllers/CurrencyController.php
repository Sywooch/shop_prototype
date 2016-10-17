<?php

namespace app\controllers;

use yii\helpers\Url;
use app\controllers\AbstractBaseController;
use app\models\CurrencyModel;
use app\helpers\SessionHelper;

/**
 * Обрабатывает запросы на изменение текущей валюты
 */
class CurrencyController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос на изменение текущей валюты
     * @return redirect
     */
    public function actionSet()
    {
        try {
            $rawCurrencyModel = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_CHANGE_CURRENCY]);
            
            if (\Yii::$app->request->isPost && $rawCurrencyModel->load(\Yii::$app->request->post())) {
                if ($rawCurrencyModel ->validate()) {
                    $currencyQuery = CurrencyModel::find();
                    $currencyQuery->extendSelect(['id', 'code', 'exchange_rate']);
                    $currencyQuery->where(['[[currency.id]]'=>$rawCurrencyModel->id]);
                    $currencyModel = $currencyQuery->one();
                    if (!$currencyModel instanceof CurrencyModel) {
                        throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'CurrencyModel']));
                    }
                    $currency = $currencyModel->attributes;
                    if (empty($currency)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $currency']));
                    }
                    SessionHelper::write(\Yii::$app->params['currencyKey'], $currency);
                }
            }
            
            return $this->redirect(Url::previous());
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
