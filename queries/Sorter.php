<?php

namespace app\queries;

use yii\base\Object;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;

class Sorter extends Object
{
    use ExceptionsTrait;
    
    /**
     * @var mixed данные, которые сортируются
     */
    private static $_data;
    
    /**
     * Парсит массив конфигурации форматирования и вызыват соответствующие методы
     * @param $data данные, которые будут сортированы
     * @param array $config массив установок для сортировки
     */
    public static function setSorting($data, array $config=[])
    {
        try {
            self::$_data = $data;
            
            switch ($config[0]) {
                case 'asort':
                    self::asort($config);
                    break;
            }
            
            return self::$_data;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Сортирует данные с помощью функции asort
     * @param array $config массив установок для сортировки
     */
    private static function asort(array $config)
    {
        try {
            asort(self::$_data);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
