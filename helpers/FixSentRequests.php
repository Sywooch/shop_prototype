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
     */
    public static function fix($event)
    {
        try {
            \Yii::$app->params['fixSentRequests'] += 1;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
