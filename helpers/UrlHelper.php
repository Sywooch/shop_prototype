<?php

namespace app\helpers;

use yii\helpers\Url;
use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;

/**
 * Расширяет методы класса yii\helpers\Url
 */
class UrlHelper extends Url
{
    /**
     * Возвращает Url предыдущей страницы, 
     * если он не найден, Url главной страницы
     */
    public static function previous($name=null)
    {
        try {
            $previous = parent::previous($name);
            
            if (is_null($previous) || (strpos(self::current(), $previous) !== false)) {
                $previous = self::to(['/products-list/index']);
            }
            
            return $previous;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
