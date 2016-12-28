<?php

namespace app\registry;

use yii\base\Object;
use app\exceptions\ExceptionsTrait;
use app\helpers\HashHelper;

/**
 * Реестр объектов
 */
class Registry extends Object
{
    use ExceptionsTrait;
    
    /**
     * @var array созданные объекты
     */
    private $items = [];
    
    /**
     * Создает и возвращает объекты
     * @param string $class имя класса, объект которого создается
     * @param array $params массив параметров
     * @return object
     */
    public function get(string $class, array $params)
    {
        try {
            $key = $this->getKey(array_merge([$class], $params));
            
            if (array_key_exists($key, $this->items) === false) {
                $object = \Yii::createObject(array_merge(['class'=>$class], $params));
                
                $this->items[$key] = $object;
            }
            
            return $this->items[$key];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    private function getKey(array $params)
    {
        try {
            $keyArray = [];
            
            foreach ($params as $param) {
                $keyArray[] = serialize($param);
            }
            
            return HashHelper::createHash($keyArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
