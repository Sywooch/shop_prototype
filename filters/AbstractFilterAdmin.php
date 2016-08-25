<?php

namespace app\filters;

use yii\base\ErrorException;
use app\filters\AbstractFilter;

/**
 * Заполняет объект корзины данными сесии
 */
abstract class AbstractFilterAdmin extends AbstractFilter
{
    /**
     * Конфигурирует \Yii::$app->filters данными из сессионного хранилища
     * @param $action выполняемое в данный момент действие
     * @return parent result
     */
    public function beforeAction($action)
    {
        try {
            if (!empty($attributes = parent::before())) {
                \Yii::configure(\Yii::$app->filters, $attributes);
            }
            return parent::beforeAction($action);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
