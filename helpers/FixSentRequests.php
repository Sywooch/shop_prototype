<?php

namespace app\helpers;

/**
 * Подсчитывает кол-во обращений к БД
 */
class FixSentRequests
{
    public static function fix($event)
    {
        \Yii::$app->params['fixSentRequests'] += 1;
    }
}
