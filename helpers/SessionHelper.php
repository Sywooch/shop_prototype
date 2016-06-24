<?php

namespace app\helpers;

use app\traits\ExceptionsTrait;

/**
 * Предоставляет методы для управления сессиями
 */
class SessionHelper
{
    use ExceptionsTrait;
    
    /**
     * Удаляет из сессии переменную, имя которой передано в качестве параметра
     * @param string $var имя переменной
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
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
        return true;
    }
}
