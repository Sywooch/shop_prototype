<?php

namespace app\collections;

use yii\base\{ErrorException,
    Model};
use yii\db\Query;
use app\exceptions\ExceptionsTrait;
use app\collections\{AbstractIterator,
    BaseTrait,
    CollectionInterface,
    PaginationInterface};

/**
 * Реализует интерфейс доступа к коллекции объектов
 */
abstract class AbstractBaseCollection extends AbstractIterator implements CollectionInterface
{
    use ExceptionsTrait, BaseTrait;
    
    /**
     * @var object Query
     */
    protected $query;
    /**
     * @var object PaginationInterface
     */
    protected $pagination;
    
    /**
     * Сохраняет объект запроса
     * @param object $query Query
     */
    public function setQuery(Query $query)
    {
        try {
            $this->query = $query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект запроса
     * @param object $query Query
     */
    public function getQuery(): Query
    {
        try {
            return !empty($this->query) ? $this->query : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Добавляет объект в коллекцию
     * @param $object Model 
     */
    public function add(Model $object)
    {
        try {
            if ($this->isEmpty() === false) {
                foreach ($this->items as $item) {
                    if ((int) $item->id === (int) $object->id) {
                        return;
                    }
                }
            }
            
            $this->items[] = $object;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объекты строк из СУБД и добавляет их в коллекцию
     * @return $this
     */
    public function getModels()
    {
        try {
            if (empty($this->query)) {
                throw new ErrorException($this->emptyError('query'));
            }
            
            if ($this->isEmpty() === true) {
                $objectsArray = $this->query->all();
                if (!empty($objectsArray)) {
                    foreach ($objectsArray as $object) {
                        $this->add($object);
                    }
                }
            }
            
            return $this;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массивы строк из СУБД и добавляет их в коллекцию
     * @return $this
     */
    public function getArrays()
    {
        try {
            if (empty($this->query)) {
                throw new ErrorException($this->emptyError('query'));
            }
            
            if ($this->isEmpty() === true) {
                $arraysArray = $this->query->asArray()->all();
                if (!empty($arraysArray)) {
                    foreach ($arraysArray as $array) {
                        $this->addArray($array);
                    }
                }
            }
            
            return $this;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает 1 объект, добавляет его в коллекцию и возвращает его клиенту
     */
    public function getModel()
    {
        try {
            if (empty($this->query)) {
                throw new ErrorException($this->emptyError('query'));
            }
            
            if ($this->isEmpty() === true) {
                $object = $this->query->one();
                if (!empty($object)) {
                    $this->add($object);
                }
            }
            
            return $this->isEmpty() === false ? $this->items[0] : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает 1 массив данных и добавляет его в коллекцию
     */
    public function getArray()
    {
        try {
            if (empty($this->query)) {
                throw new ErrorException($this->emptyError('query'));
            }
            
            if ($this->isEmpty() === true) {
                $array = $this->query->asArray()->one();
                if (!empty($array)) {
                    $this->addArray($array);
                }
            }
            
            return $this->isEmpty() === false ? $this->items[0] : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет объект пагинации
     * @param object $pagination PaginationInterface
     */
    public function setPagination(PaginationInterface $pagination)
    {
        try {
            $this->pagination = $pagination;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект пагинации
     * @return PaginationInterface/null
     */
    public function getPagination()
    {
        try {
            return !empty($this->pagination) ? $this->pagination : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
