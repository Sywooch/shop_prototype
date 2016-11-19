<?php

namespace app\models;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;

/**
 * Реализует интерфейс Iterator для доступа к коллекции товаров в корзине
 */
abstract class AbstractBaseCollection extends Object implements \Iterator
{
    use ExceptionsTrait;
    
    /**
     * Текущая позиция итерации
     */
    private $position = 0;
    
    /**
     * Задает начальную позицию итерации
     */
    public function __construct()
    {
        try {
            $this->position = 0;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает итератор на первый элемент
     */
    function rewind()
    {
        try {
            $this->position = 0;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает итерируемый объект
     */
    function current()
    {
        try {
            return $this->items[$this->position];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает ключ текущего элемента
     */
    function key()
    {
        try {
            return $this->position;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Переходит к следующему элементу
     */
    function next()
    {
        try {
            ++$this->position;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет корректность позиции
     */
    function valid()
    {
        try {
            return isset($this->items[$this->position]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Перехватвает обращение к несуществующему свойству,
     * обходит все объекты $this->items и возвращает сумму результатов
     * @param string $property имя свойства
     * @return numeric/null
     */
    public function __get($property)
    {
        try {
            if (!empty($this->items)) {
                $result = 0;
                foreach ($this->items as $item) {
                    if (property_exists($item, $property) && is_numeric($item->$property)) {
                        $result += $item->$property;
                    }
                }
            }
            
            return $result ?? null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
