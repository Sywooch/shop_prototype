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
            $currencyArray = SessionHelper::read(\Yii::$app->params['currencyKey']);
            
            if (empty($currencyArray)) {
                $currencyQuery = CurrencyModel::find();
                $currencyQuery->extendSelect(['id', 'code', 'exchange_rate', 'main']);
                $currencyQuery->where(['[[currency.main]]'=>true]);
                $currencyQuery->asArray();
                $currencyArray = $currencyQuery->one();
                if (!empty($currencyArray)) {
                    SessionHelper::write(\Yii::$app->params['currencyKey'], $currencyArray);
                }
            }
            
            \Yii::configure(\Yii::$app->currency, $currencyArray);
            
            return parent::beforeAction($action);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
