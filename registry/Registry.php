<?php

namespace app\registry;

use yii\base\{ErrorException,
    Object};
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
    public function get(string $class, array $params=[])
    {
        try {
            if (empty($class)) {
                throw new ErrorException($this->emptyError('class'));
            }
            
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
    
    /**
     * Создает ключ для сохранения созданного объект в реестре
     * Ключ создается из имени класса и значений передаваемых 
     * для создания объекта параметров
     * @param array $params массив данных для создания ключа
     * @return string
     */
    private function getKey(array $params): string
    {
        try {
            if (empty($params)) {
                throw new ErrorException($this->emptyError('params'));
            }
            
            $keyArray = [];
            
            foreach ($params as $param) {
                $keyArray[] = serialize($param);
            }
            
            return HashHelper::createHash($keyArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Очищает данные Registry::items
     */
    public function clean()
    {
        try {
            $this->items = [];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
