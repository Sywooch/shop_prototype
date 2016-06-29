<?php

namespace app\helpers;

use app\traits\ExceptionsTrait;
use yii\base\ErrorException;

/**
 * Подсчитывает кол-во обращений к БД
 */
class FixSentRequests
{
    use ExceptionsTrait;
    
    /**
     * Сумирует кол-во совершенных запросов к БД
     * @param $event информация о произошедшем событии
     * @return boolean
     */
    public static function fix($event)
    {
        try {
            if (!isset(\Yii::$app->params['fixSentRequests'])) {
                throw new ErrorException('Не установлена переменная fixSentRequests!');
            }
            \Yii::$app->params['fixSentRequests'] += 1;
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
