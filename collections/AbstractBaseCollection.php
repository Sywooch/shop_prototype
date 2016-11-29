<?php

namespace app\collections;

use yii\base\{ErrorException,
    Model};
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\queries\PaginationInterface;
use app\collections\AbstractIterator;

/**
 * Реализует интерфейс Iterator для доступа к коллекции сущностей
 */
abstract class AbstractBaseCollection extends AbstractIterator
{
    use ExceptionsTrait;
    
    /**
     * @var object PaginationInterface
     */
    private $pagination;
    
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
     * Возвращает bool в зависимости от того, пуст или нет $this::items
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
    
    /**
     * Проверяет наличие объекта в коллекции $this::items
     * @return bool
     */
    public function hasEntity(Model $object): bool
    {
        try {
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function update(Model $object)
    {
        try {
            
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
    
    /**
     * Возвращает данные из $this::items в формате ключ=>значение, 
     * где значения одного из свойств, становятся ключами возвращаемого массива, 
     * а значения второго - значениями этих ключей
     * @param string $key имя свойства, значения которого станут ключами
     * @param string $value имя свойства, значения которого станут значениями
     * @return array
     */
    public function map(string $key, string $value): array
    {
        try {
            return ArrayHelper::map($this->items, $key, $value);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Сортирует объекты коллекции $this::items
     * @param string $key имя свойства, по значениям которого будет выполнена сортировка
     * @param string $type флаг, определяющий тип сортировки SORT_ASC / SORT_DESC
     */
    public function sort($key, $type=SORT_ASC)
    {
        try {
            ArrayHelper::multisort($this->items, $key, $type);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
