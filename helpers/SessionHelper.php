<?php

namespace app\helpers;

use yii\base\ErrorExceptions;
use app\exceptions\ExceptionsTrait;

/**
 * Коллекция методов для управления сессиями
 */
class SessionHelper
{
    /**
     * Добавляет переменную к данным сессии
     * @param string $name имя переменной
     * @param mixed $data данные для добавления в сессию
     * @return bool
     */
    public static function write(string $name, $data): bool
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
     * @param string $name имя переменной
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
            
            return $data ?? false;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Добавляет Flash-сообщение к данным сессии
     * @param string $name имя переменной
     * @param mixed $data данные для добавления в сессию
     * @return bool
     */
    public static function writeFlash(string $name, $data): bool
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
     * @param string $name имя переменной
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
            
            return $data ?? false;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Удаляет переменные из сессии
     * @param array $keysArray массив имен переменных
     * @return bool
     */
    public static function remove(array $keysArray): bool
    {
        try {
            $session = \Yii::$app->session;
            $session->open();
            foreach ($keysArray as $key) {
                if ($session->has($key)) {
                    $session->remove($key);
                }
            }
            $session->close();
            
            return true;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет существование записи по ключу
     * @param string $name имя переменной
     * @return bool
     */
    public static function has(string $name): bool
    {
        try {
            $session = \Yii::$app->session;
            $session->open();
            if ($session->has($key)) {
                return true;
            }
            $session->close();
            
            return false;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
