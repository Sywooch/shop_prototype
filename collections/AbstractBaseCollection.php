<?php

namespace app\collections;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\collections\{AbstractIterator,
    CollectionInterface};

/**
 * Базовый класс коллекций
 */
abstract class AbstractBaseCollection extends AbstractIterator implements CollectionInterface
{
    use ExceptionsTrait;
    
    /**
     * Добавляет объект в коллекцию
     * @param $object Model 
     */
    public function add(Model $object)
    {
        try {
            $this->items[] = $object;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Добавляет массив в коллекцию
     * @param array $array 
     */
    public function addArray(array $array)
    {
        try {
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
     * Сортирует объекты коллекции static::items
     * @param string $key имя свойства, по значениям которого будет выполнена сортировка
     * @param string $type флаг, определяющий тип сортировки SORT_ASC / SORT_DESC
     */
    public function multisort(string $key, $type=SORT_ASC)
    {
        try {
            if ($this->isEmpty() === true) {
                throw new ErrorException($this->emptyError('items'));
            }
            
            ArrayHelper::multisort($this->items, $key, $type);
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
            if ($this->isEmpty() === true) {
                throw new ErrorException($this->emptyError('items'));
            }
            
            return ArrayHelper::map($this->items, $key, $value);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные коллекции в виде массива объектов
     * @return array
     */
    public function asArray(): array
    {
        try {
            return $this->items;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает количество элементов коллекции
     * @return int
     */
    public function count(): int
    {
        try {
            return count($this->items);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
