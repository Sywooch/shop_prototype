<?php

namespace app\queries;

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
     * Парсит массив конфигурации форматирования и вызыват соответствующие методы
     * @param $data данные, которые будут форматированы
     * @param array $config массив установок для форматтеров
     */
    public static function setFormat($data, array $config=[])
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
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Форматирует данные с помощью метода ArrayHelper::map
     * @param array $config массив установок для форматирования
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
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
