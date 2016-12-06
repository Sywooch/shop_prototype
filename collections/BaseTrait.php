<?php

namespace app\collections;

use yii\base\{ErrorException,
    Model};
use yii\helpers\ArrayHelper;

/**
 * Коллекция методов, общих для всех коллекций
 */
trait BaseTrait
{
    /**
     * Добавляет массив в коллекцию
     * @param array $array 
     */
    public function addArray(array $array)
    {
        try {
            if ($this->isEmpty() === false) {
                foreach ($this->items as $item) {
                    if ((int) $item['id'] === (int) $array['id']) {
                        return;
                    }
                }
            }
            
            $this->items[] = $array;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает bool в зависимости от того, пуст или нет static::items
     * @return bool
     */
    public function isEmpty(): bool
    {
        try {
            return empty($this->items) ? true : false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает bool в зависимости от содержит или нет static::items массивы
     * @return bool
     */
    public function isArrays(): bool
    {
        try {
            foreach ($this->items as $item) {
                if (is_array($item) === false) {
                    return false;
                }
            }
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает bool в зависимости от содержит или нет static::items объекты
     * @return bool
     */
    public function isObjects(): bool
    {
        try {
            foreach ($this->items as $item) {
                if (is_object($item) === false) {
                    return false;
                }
            }
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные из static::items в формате ключ=>значение, 
     * где значения одного из свойств, становятся ключами возвращаемого массива, 
     * а значения второго - значениями этих ключей
     * @param string $key имя свойства, значения которого станут ключами
     * @param string $value имя свойства, значения которого станут значениями
     * @return array
     */
    public function map(string $key, string $value): array
    {
        try {
            if ($this->isEmpty()) {
                throw new ErrorException($this->emptyError('items'));
            }
            
            return ArrayHelper::map($this->items, $key, $value);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Сортирует объекты коллекции static::items
     * @param string $key имя свойства, по значениям которого будет выполнена сортировка
     * @param string $type флаг, определяющий тип сортировки SORT_ASC / SORT_DESC
     */
    public function sort(string $key, $type=SORT_ASC)
    {
        try {
            if ($this->isEmpty()) {
                throw new ErrorException($this->emptyError('items'));
            }
            
            ArrayHelper::multisort($this->items, $key, $type);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет существование объекта в коллекции
     * @param $object Model
     * @return bool
     */
    public function hasEntity(Model $object): bool
    {
        try {
            if ($this->isEmpty() === false) {
                foreach ($this->items as $item) {
                    if (is_array($item)) {
                        if ($item['id'] === $object->id) {
                            return true;
                        }
                    } else {
                        if ($item->id === $object->id) {
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
    
    /**
     * Обновляет элемент коллекции
     * @param $object Model
     */
    public function update(Model $object)
    {
        try {
            if ($this->isEmpty() === false) {
                foreach ($this->items as $key=>$item) {
                    if (is_array($item)) {
                        if ($item['id'] === $object->id) {
                            unset($this->items[$key]);
                            $this->addArray($object->toArray());
                        }
                    } else {
                        if ($item->id === $object->id) {
                            unset($this->items[$key]);
                            $this->add($object);
                        }
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
