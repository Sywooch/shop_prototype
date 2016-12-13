<?php

namespace app\collections;

use yii\base\Object;
use app\exceptions\ExceptionsTrait;

/**
 * Реализует интерфейс итератора для коллекции
 */
abstract class AbstractIterator extends Object implements \Iterator
{
    use ExceptionsTrait;
    
    /**
     * @var array элементы коллекции
     */
    protected $items = [];
    /**
     * @var int текущая позиция итерации
     */
    private $position = 0;
    
    /**
     * Задает начальную позицию итерации
     */
    public function __construct(array $config=[])
    {
        try {
            $this->position = 0;
            parent::__construct($config);
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
     * Возвращает текущий элемент итерации из $this::items
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
}
