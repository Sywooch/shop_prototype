<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;

/**
 * Коллекция методов для обработки строк
 */

class StringHelper
{
    /**
     * Удаляет из строки URL часть, представляющую номер страницы
     * @param string $url строка URL
     * @return string
     */
    public static function cutPage(string $url): string
    {
        try {
            if (preg_match('/^(.*)-\d+(\?search=.*)*$/', $url, $matches) === 1) {
                $url = count($matches) > 2 ? $matches[1] . $matches[2] : $matches[1];
            }
            
            return $url;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
