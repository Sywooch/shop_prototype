<?php

namespace app\filters;

use yii\base\{ActionFilter,
    ErrorException};
use app\exceptions\ExceptionsTrait;
use app\helpers\SessionHelper;
use app\models\CurrencyModel;

/**
 * Устанавливает валюту для текущего запроса
 */
class CurrencyFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * Получает данные для валюты из сессии, 
     * если терпит неудачу, загружает валюту по умолчанию 
     * @param object $action объект текущего действия
     * @return bool
     */
    public function beforeAction($action)
    {
        try {
            $currency = SessionHelper::read(\Yii::$app->params['currencyKey']);
            
            if (empty($currency)) {
                $currencyQuery = CurrencyModel::find();
                $currencyQuery->extendSelect(['id', 'code', 'exchange_rate', 'main']);
                $currencyQuery->where(['[[currency.main]]'=>true]);
                $currencyModel = $currencyQuery->one();
                if (!$currencyModel instanceof CurrencyModel) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'CurrencyModel']));
                }
                $currency = $currencyModel->attributes;
                if (empty($currency)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $currency']));
                }
                if (!SessionHelper::write(\Yii::$app->params['currencyKey'], $currency)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'SessionHelper::write']));
                }
            }
            
            \Yii::configure(\Yii::$app->currency, $currency);
            
            return parent::beforeAction($action);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
