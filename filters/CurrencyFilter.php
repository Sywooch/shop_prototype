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
                $currencyModel = $currencyQuery->oneArray();
                if (!empty($currencyModel)) {
                    $currency = [
                        'id'=>$currencyModel->id, 
                        'code'=>$currencyModel->code, 
                        'exchange_rate'=>$currencyModel->exchange_rate, 
                        'main'=>$currencyModel->main
                    ];
                    SessionHelper::write(\Yii::$app->params['currencyKey'], $currency);
                }
                
            }
            
            \Yii::configure(\Yii::$app->currency, $currency);
            
            return parent::beforeAction($action);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
