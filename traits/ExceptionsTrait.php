<?php

namespace app\traits;

use yii\base\ErrorException;

/**
 * Предоставляет методы записи логов и выброса маркированных исключений
 */
trait ExceptionsTrait
{
    /**
     * Принимает любое исключение, маркирует и выбрасывает как yii\base\ErrorException
     * @param $e экзкмпляр \Exception
     * @param $method полностью определенное имя метода, поймавшего исключение
     */
    public function throwException(\Exception $e, $method)
    {
        throw new ErrorException(\Yii::t('base/errors', "Method error {method}!\n", ['method'=>$method]) . $e->getMessage());
    }
    
    /**
     * Статический метод
     * Принимает любое исключение, маркирует и выбрасывает как yii\base\ErrorException
     * @param $e экзкмпляр \Exception
     * @param $method полностью определенное имя метода, поймавшего исключение
     */
    public static function throwStaticException(\Exception $e, $method)
    {
        throw new ErrorException(\Yii::t('base/errors', "Method error {method}!\n", ['method'=>$method]) . $e->getMessage());
    }
    
    /**
     * Записывает в логи исключение уровня error
     * @param $e экзкмпляр \Exception
     * @param $method полностью определенное имя метода, поймавшего исключение
     */
    public function writeErrorInLogs(\Exception $e, $method)
    {
        \Yii::error(\Yii::t('base/errors', "Method error {method}!\n", ['method'=>$method]) . $e->getMessage(), $method);
    }
}
