<?php

namespace app\collections;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;
use app\collections\{AbstractIterator,
    BaseTrait,
    SessionCollectionInterface};

/**
 * Реализует интерфейс доступа к коллекции объектов, 
 * полученной из сессионного хранилища
 */
abstract class AbstarctBaseSessionCollection extends AbstractIterator implements SessionCollectionInterface
{
    use ExceptionsTrait, BaseTrait;
    
    /**
     * Получает объекты из сессии и добавляет их в коллекцию
     * @return $this
     */
    abstract public function getModels();
    
    /**
     * Возвращает 1 объект из коллекции
     */
    abstract public function getModel();
    
    /**
     * Возвращает 1 массив из коллекции
     */
    public function getArray()
    {
        try {
            if ($this->isEmpty()) {
                throw new ErrorException($this->emptyError('items'));
            }
            if ($this->isArrays() === false) {
                throw new ErrorException($this->invalidError('items'));
            }
            
            return $this->items[0];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
