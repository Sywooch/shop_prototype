<?php

namespace app\filters;

use yii\base\{ActionFilter,
    ErrorException};
use app\exceptions\ExceptionsTrait;
use app\helpers\{HashHelper,
    SessionHelper};

/**
 * Применяет фильтры к выборке ProductsModel
 */
class CustomerFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * Восстанавливает ранее сохраненное состояние массива, 
     * содержащего данные покупателя 
     * @param object $action объект текущего действия
     * @return bool
     */
    public function beforeAction($action)
    {
        try {
            $customerKey = HashHelper::createHash([\Yii::$app->params['customerKey'], \Yii::$app->user->id ?? '']);
            \Yii::$app->params['customerArray'] = SessionHelper::read($customerKey) ?? [];
            
            return parent::beforeAction($action);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
