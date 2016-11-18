<?php

namespace app\models;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;

/**
 * Реализует интерфейс Iterator для доступа к коллекции товаров в корзине
 */
abstract class AbstractBaseComposit implements \Iterator
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
     * обеспечивая возможность обратиться к методу с помощью синтаксиса свойств
     * @param string $property имя несуществующего свойства
     */
    /*public function __get($property)
    {
        try {
            $method = 'get' . mb_convert_case($property, MB_CASE_TITLE);
            return method_exists($this, $method) ? $this->$method() : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }*/
}
