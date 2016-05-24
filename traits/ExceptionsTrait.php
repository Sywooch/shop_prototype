<?php

namespace app\traits;

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
        throw new ErrorException("Ошибка при вызове метода {$method}\n" . $e->getMessage());
    }
    
    /**
     * Записывает в логи исключение уровня error
     * @param $e экзкмпляр \Exception
     * @param $method полностью определенное имя метода, поймавшего исключение
     */
    public function writeErrorInLogs(\Exception $e, $method)
    {
        \Yii::error("Ошибка при вызове метода {$method}\n" . $e->getMessage(), $method);
    }
}
