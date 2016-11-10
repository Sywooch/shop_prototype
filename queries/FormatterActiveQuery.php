<?php

namespace app\queries;

use yii\base\Object;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;

class FormatterActiveQuery extends Object
{
    use ExceptionsTrait;
    
    private static $_data = [];
    
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
    private static function map(array $task)
    {
        try {
            self::$_data = ArrayHelper::map(self::$_data, $task['key'], $task['value']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
