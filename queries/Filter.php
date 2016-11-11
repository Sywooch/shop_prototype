<?php

namespace app\queries;

use yii\base\Object;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;

class Filter extends Object
{
    use ExceptionsTrait;
    
    /**
     * @var object ActiveQuery
     */
    private static $_query;
    
    /**
     * Парсит массив конфигурации фильтров и вызыват соответствующие методы
     * @param $query ActiveQuery, к которому будут применены фильтры
     * @param array $config массив установок для фильтров
     */
    public static function setFilter($query, array $config=[])
    {
        try {
            self::$_query = $query;
            
            foreach ($config as $key=>$task) {
                switch ($key) {
                    case 'where':
                        self::where($task);
                        break;
                }
            }
            
            return self::$_query;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Применяет фильтр Query::where
     * @param array $config массив установок для фильтра
     */
    private static function where(array $configs)
    {
        try {
            foreach ($configs as $config) {
                self::$_query->andWhere($config);
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
