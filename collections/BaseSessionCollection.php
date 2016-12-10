<?php

namespace app\collections;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;
use app\collections\{AbstractIterator,
    BaseTrait,
    SessionCollectionInterface};

/**
 * Реализует интерфейс доступа к коллекции объектов, 
 * полученной из сессионного хранилища
 */
class BaseSessionCollection extends AbstractIterator implements SessionCollectionInterface
{
    use ExceptionsTrait, BaseTrait;
    
    /**
     * Получает объекты из сессии и добавляет их в коллекцию
     * @param string $class имя класса, объект которого будет создан
     * @return SessionCollectionInterface
     */
    public function getModels(string $class): SessionCollectionInterface
    {
        try {
            if ($this->isEmpty() === false) {
                if ($this->isArrays() === false) {
                    throw new ErrorException($this->invalidError('items'));
                }
                
                $objectsArray = [];
                
                foreach ($this->items as $item) {
                    $model = new $class();
                    $model->attributes = $item;
                    $objectsArray[] = $model;
                }
                
                $this->items = $objectsArray;
            }
            
            return $this;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает 1 объект из коллекции
     * @param string $class имя класса, объект которого будет создан
     * @return Model
     */
    public function getModel(string $class): Model
    {
        try {
            if ($this->isEmpty()) {
                throw new ErrorException($this->emptyError('items'));
            }
            if ($this->isArrays() === false) {
                throw new ErrorException($this->invalidError('items'));
            }
            
            $model = new $class();
            $model->attributes = $this->items[0];
            
            return $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
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
