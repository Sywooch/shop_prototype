<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;

/**
 * Коллекция методов для создания хеша
 */
class HashHelper
{
    /**
     * Конструирует хеш с помощью функции sha1
     * @param array $inputArray массив данных для конструирования хеша
     * @return string
     */
    public static function createHash(Array $inputArray): string
    {
        try {
            if (empty($inputArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'Array $inputArray']));
            }
            
            $inputArray[] = \Yii::$app->params['hashSalt'];
            return sha1(implode('', $inputArray));
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
