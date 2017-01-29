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
    
    /**
     * Возвращает дату в формате Unix Timestamp, 
     * отнимая от текущей переданное параметром количество дней
     * время выставлено в 00:00:00
     * @param int $days количество отнимаемых от текущей даты дней
     * @return int
     */
    public static function getDaysAgo00(int $days): int
    {
        try {
            $today = self::getToday00();
            
            $diff = (60 * 60 * 24) * $days;
            
            return $today - $diff;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
