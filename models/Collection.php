<?php

namespace app\models;

use yii\base\{ErrorException,
    Model};
use app\models\{AbstractBaseCollection,
    CollectionInterface};

/**
 * Реализует интерфейс доступа к данным коллекции сущностей
 */
class Collection extends AbstractBaseCollection implements CollectionInterface
{
    /**
     * Коллекция сущностей
     */
    protected $items = [];
    
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
     * Возвращает true, false в зависимости от того, пуст или нет Collection::items
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
     * Возвращает сущность по ключу
     * @param string $key имя ключа
     * @param mixed $value значение ключа
     * @return object/null
     */
    public function getByKey(string $key, $value)
    {
        try {
            if (!empty($this->items)) {
                foreach ($this->items as $item) {
                    if (!empty($item->$key) && $item->$key === $value) {
                        return $item;
                    }
                }
            }
            
            return null;
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
    
    public function hasEntity(Model $object)
    {
        try {
            if (!empty($this->items)) {
                foreach ($this->items as $item) {
                    if ($item->id_product === $object->id_product) {
                        if (empty(array_diff([$object->id_color, $object->id_size], [$item->id_color, $item->id_size]))) {
                            return true;
                        }
                    }
                }
            }
            
            return false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function update(Model $object)
    {
        try {
            if (!empty($this->items)) {
                foreach ($this->items as $item) {
                    if ($item->id_product === $object->id_product) {
                        $item->quantity += $object->quantity;
                        return true;
                    }
                }
            }
            
            return false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
