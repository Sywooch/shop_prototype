<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;

/**
 * Коллекция методов для создания дат
 */
class DateHelper
{
    /**
     * Возвращает текущую дату в формате Unix Timestamp
     * время выставлено в 00:00:00
     * @return int
     */
    public static function getToday00(): int
    {
        try {
            $today = new \DateTime(sprintf('%s %s', (new \DateTime())->format('Y-m-d'), '00:00:00'));
            
            return $today->getTimestamp();
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
