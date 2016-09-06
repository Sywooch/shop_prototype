<?php

namespace app\helpers;

use app\traits\ExceptionsTrait;

/**
 * Коллекция методов для управления сессиями
 */
class SessionHelper
{
    use ExceptionsTrait;
    
    /**
     * Удаляет переменные из сессии
     * @param array $varsArray массив имен переменных
     * @return boolean
     */
    public static function removeVarFromSession(Array $varsArray)
    {
        try {
            $session = \Yii::$app->session;
            $session->open();
            foreach ($varsArray as $var) {
                if ($session->has($var)) {
                    $session->remove($var);
                }
            }
            $session->close();
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
