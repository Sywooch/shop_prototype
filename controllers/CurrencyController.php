<?php

namespace app\controllers;

use yii\base\ErrorException;
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
                    $currencyQuery->asArray();
                    $currencyArray = $currencyQuery->one();
                    if (!is_array($currencyArray) || empty($currencyArray)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $currencyArray']));
                    }
                    SessionHelper::write(\Yii::$app->params['currencyKey'], $currencyArray);
                }
            }
            
            return $this->redirect(Url::previous());
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            if (YII_ENV_DEV) {
                $this->throwException($t, __METHOD__);
            } else {
                return $this->redirect(Url::previous());
            }
        }
    }
}
