<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;

/**
 * Предоставляет функциональность для создания хеша
 */
class HashHelper
{
    use ExceptionsTrait;
    
    /**
     * Конструирует хеш с помощью функции md5
     * @param array $inputArray массив данных для конструирования хеша
     * @return string результирующий хеш
     */
    public static function createHash(Array $inputArray)
    {
        try {
            $inputString = implode('-', $inputArray);
            return md5($inputString);
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
