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
     * Конструирует ключ для ссылки, по которой произойдет смена пароля
     * @param array $inputArray массив данных для конструирования ключа
     * @return string
     */
    public static function createKeyTempPass(array $inputArray): string
    {
        try {
            return self::createHash(array_merge($inputArray, [time(), random_bytes(10)]));
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует временный пароль при восстановлении
     * @param int $length длина возвращаемого пароля
     * @return string
     */
    public static function createTempPass(int $length=10): string
    {
        try {
            $password = self::createHash([time(), random_bytes(10)]);
            return substr($password, 0, $length);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
