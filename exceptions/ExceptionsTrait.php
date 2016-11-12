<?php

namespace app\exceptions;

use yii\base\ErrorException;

/**
 * Предоставляет методы записи логов и выброса маркированных исключений
 */
trait ExceptionsTrait
{
    /**
     * Принимает любое исключение, маркирует и выбрасывает как yii\base\ErrorException
     * @param $t экземпляр \Throwable
     * @param $method полностью определенное имя метода, поймавшего исключение
     */
    public function throwException(\Throwable $t, $method): ErrorException
    {
        throw new ErrorException(\Yii::t('base/errors', 'Method error {method}' . PHP_EOL, ['method'=>$method]) . $t->getMessage());
    }
    
    /**
     * Статический метод
     * Принимает любое исключение, маркирует и выбрасывает как yii\base\ErrorException
     * @param $t экземпляр \Throwable
     * @param $method полностью определенное имя метода, поймавшего исключение
     */
    public static function throwStaticException(\Throwable $t, $method): ErrorException
    {
        throw new ErrorException(\Yii::t('base/errors', 'Method error {method}' . PHP_EOL, ['method'=>$method]) . $t->getMessage());
    }
    
    /**
     * Записывает в логи исключение уровня error
     * @param $t экземпляр \Throwable
     * @param $method полностью определенное имя метода, поймавшего исключение
     */
    public function writeErrorInLogs(\Throwable $t, $method)
    {
        \Yii::error(\Yii::t('base/errors', 'Method error {method}' . PHP_EOL, ['method'=>$method]) . $t->getMessage(), $method);
    }
    
    /**
     * Записывает в логи исключение уровня error
     * @param $message сообщение для записи
     * @param $method полностью определенное имя метода, поймавшего исключение
     */
    public function writeMessageInLogs(string $message, $method)
    {
        \Yii::error(\Yii::t('base/errors', 'Method error {method}' . PHP_EOL, ['method'=>$method]) . $message, $method);
    }
    
    /**
     * Конструирует строку сообщения об ошибке "Ошибка при вызове метода"
     * @return string
     */
    public static function methodError(string $placeholder): string
    {
        return \Yii::t('base/errors', 'Method error {placeholder}', ['placeholder'=>$placeholder]);
    }
    
    /**
     * Конструирует строку сообщения об ошибке "Отсутствуют необходимые данные"
     * @return string
     */
    public static function emptyError(string $placeholder): string
    {
        return \Yii::t('base/errors', 'Missing required data {placeholder}', ['placeholder'=>$placeholder]);
    }
    
    /**
     * Конструирует строку сообщения об ошибке "Получен неверный тип данных"
     * @return string
     */
    public static function invalidError(string $placeholder): string
    {
        return \Yii::t('base/errors', 'Received invalid data type instead {placeholder}', ['placeholder'=>$placeholder]);
    }
}
