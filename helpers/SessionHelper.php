<?php

namespace app\helpers;

use app\exceptions\ExceptionsTrait;

/**
 * Коллекция методов для управления сессиями
 */
class SessionHelper
{
    /**
     * Добавляет переменную к данным сессии
     * @param array $name имя переменной
     * @param mixed $data данные для добавления в сессию
     * @return bool
     */
    public static function write(string $name, $data)
    {
        try {
            $session = \Yii::$app->session;
            $session->open();
            $session->set($name, $data);
            $session->close();
            
            return true;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Читает сообщение из сессии
     * @param array $name имя переменной
     * @return mixed
     */
    public static function read(string $name)
    {
        try {
            $session = \Yii::$app->session;
            $session->open();
            if ($session->has($name)) {
                $data = $session->get($name);
            }
            $session->close();
            
            return isset($data) ? $data : false;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Добавляет Flash-сообщение к данным сессии
     * @param array $name имя переменной
     * @param mixed $data данные для добавления в сессию
     * @return bool
     */
    public static function writeFlash(string $name, $data)
    {
        try {
            $session = \Yii::$app->session;
            $session->open();
            $session->setFlash($name, $data);
            $session->close();
            
            return true;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Читает Flash-сообщение из сессии
     * @param array $name имя переменной
     * @return mixed
     */
    public static function readFlash(string $name)
    {
        try {
            $session = \Yii::$app->session;
            $session->open();
            if ($session->hasFlash($name)) {
                $data = $session->getFlash($name);
            }
            $session->close();
            
            return isset($data) ? $data : false;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Удаляет переменные из сессии
     * @param array $varsArray массив имен переменных
     * @return bool
     */
    public static function remove(Array $varsArray)
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
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
