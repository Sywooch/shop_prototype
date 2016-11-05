<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;
use app\models\EmailsModel;

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
    public static function createHash(array $inputArray): string
    {
        try {
            if (!empty($inputArray)) {
                if (!empty(\Yii::$app->params['hashSalt'])) {
                    $inputArray[] = \Yii::$app->params['hashSalt'];
                }
            }
            
            return !empty($inputArray) ? sha1(implode('', $inputArray)) : '';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
