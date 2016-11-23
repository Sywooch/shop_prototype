<?php

namespace app\models;

use yii\base\{ErrorException,
    Model,
    Object};
use app\exceptions\ExceptionsTrait;

/**
 * Реализует интерфейс Iterator для доступа к коллекции сущностей
 */
abstract class AbstractBaseCollection extends Object implements \Iterator
{
    use ExceptionsTrait;
    
    /**
     * Коллекция сущностей
     */
    protected $items = [];
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
     * Добавляет сущность в коллекцию
     * @param object $model Model
     */
    public function add(Model $model)
    {
        try {
            $this->items[] = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает bool в зависимости от того, пуст или нет Collection::items
     */
    public function isEmpty()
    {
        try {
            return empty($this->items) ? true : false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает сущности в виде массива
     * @return array
     */
    public function getArray(): array
    {
        try {
            $result = [];
            foreach ($this->items as $item) {
                $result[] = $item->toArray();
            }
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
