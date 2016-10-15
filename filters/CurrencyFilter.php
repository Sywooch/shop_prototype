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
     * Пытается получить значение валюты из сессии, 
     * если терпит неудачу, загружает валюту по умолчанию 
     * @param object $action объект текущего действия
     * @return bool
     */
    public function beforeAction($action)
    {
        try {
            $currency = SessionHelper::read('currency');
            
            if (empty($currency)) {
                $currencyQuery = CurrencyModel::find();
                $currencyQuery->extendSelect(['id', 'currency', 'exchange_rate', 'main']);
                $currencyQuery->where(['[[main]]'=>true]);
                $currencyModel = $currencyQuery->one();
                if (!$currencyModel instanceof CurrencyModel) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'CurrencyModel']));
                }
                $currency = $currencyModel->attributes;
            }
            
            \Yii::configure(\Yii::$app->currency, $currency);
            
            return parent::beforeAction($action);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
