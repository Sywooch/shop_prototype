<?php

namespace app\helpers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\exceptions\ExceptionsTrait;
use app\models\EmailsModel;
use app\helpers\StringHelper;

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
            if (empty($inputArray)) {
                throw new ErrorException(ExceptionsTrait::staticEmptyError('inputArray'));
            }
            
            $inputArray[] = \Yii::$app->params['hashSalt'];
            
            return sha1(implode('', $inputArray));
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует ключ для сохранения товарных фильтров
     * @return string
     */
    public static function createFiltersKey(string $url): string
    {
        try {
            return self::createHash([StringHelper::cutPage($url), \Yii::$app->user->id ?? '']);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует ключ для сохранения текущей валюты
     * @return string
     */
    public static function createCurrencyKey(): string
    {
        try {
            return self::createHash([\Yii::$app->params['currencyKey'], \Yii::$app->user->id ?? '']);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует ключ для сохранения корзины покупок
     * @return string
     */
    public static function createCartKey(): string
    {
        try {
            return self::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует ключ для сохранения данных покупателя
     * @return string
     */
    public static function createCartCustomerKey(): string
    {
        try {
            return self::createHash([\Yii::$app->params['customerKey'], \Yii::$app->user->id ?? '']);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует ключ для сохранения данных IP сессии
     * @return string
     */
    public static function createSessionIpKey(): string
    {
        try {
            return self::createHash([\Yii::$app->params['userIP'], \Yii::$app->user->id ?? '']);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует случайную строку заданной длины
     * @param int $length длина возвращаемой строки
     * @return string
     */
    public static function randomString(int $length=10): string
    {
        try {
            $string = self::createHash([time(), random_bytes(20)]);
            $length = $length > 40 ? 40 : $length;
            return mb_substr($string, 0, $length, 'UTF-8');
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
