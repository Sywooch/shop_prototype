<?php

namespace app\helpers;

use yii\base\Object;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;

class Formatter extends Object
{
    use ExceptionsTrait;
    
    /**
     * @var mixed данные, которые форматируются
     */
    private static $_data;
    
    /**
     * Парсит массив конфигурации форматирования и вызыват соответствующие настройкам методы
     */
    public static function setFormat($data, array $config)
    {
        try {
            self::$_data = $data;
            
            switch ($config[0]) {
                case 'map':
                    self::map($config);
                    break;
            }
            
            return self::$_data;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Форматирует данные с помощью метода ArrayHelper::map
     * @param array $task массив данных для форматирования
     * - $key имя поля, которое станет ключом
     * - $value имя поля, которое станет значением
     */
    private static function map(array $config)
    {
        try {
            if (!empty($config['key']) && !empty($config['value'])) {
                self::$_data = ArrayHelper::map(self::$_data, $config['key'], $config['value']);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
