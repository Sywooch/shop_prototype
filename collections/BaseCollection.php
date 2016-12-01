<?php

namespace app\collections;

use yii\base\{ErrorException,
    Model};
use yii\db\Query;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\collections\{AbstractIterator,
    CollectionInterface,
    PaginationInterface};

/**
 * Управляет коллекцией объектов
 */
class BaseCollection extends AbstractIterator implements CollectionInterface
{
    use ExceptionsTrait;
    
    /**
     * @var object Query
     */
    private $query;
    /**
     * @var object PaginationInterface
     */
    private $pagination;
    
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
            if (!empty($this->items)) {
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
     * Добавляет массив в коллекцию
     * @param array $array 
     */
    public function addArray(array $array)
    {
        try {
            if (!empty($this->items)) {
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
     * Получает массив обектов Model и добавляет их в коллекцию
     */
    public function getModels()
    {
        try {
            if (empty($this->query)) {
                throw new ErrorException(ExceptionsTrait::emptyError('query'));
            }
            
            if ($this->isEmpty()) {
                $objectsArray = $this->query->all();
                if (!empty($objectsArray)) {
                    foreach ($objectsArray as $object) {
                        $this->add($object);
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив массивов, представляющих строки в СУБД и добавляет их в коллекцию
     */
    public function getArrays()
    {
        try {
            if (empty($this->query)) {
                throw new ErrorException(ExceptionsTrait::emptyError('query'));
            }
            
            if ($this->isEmpty()) {
                $arraysArray = $this->query->asArray()->all();
                if (!empty($arraysArray)) {
                    foreach ($arraysArray as $array) {
                        $this->addArray($array);
                    }
                }
            }
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
    public function sort(string $key, $type=SORT_ASC)
    {
        try {
            ArrayHelper::multisort($this->items, $key, $type);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет существование в коллекции сущности с переданным данными
     * @param $object Model
     * @return bool
     */
    public function hasEntity(Model $object): bool
    {
        try {
            if (!empty($this->items)) {
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
     * Обновляет данные сущности 
     * @param $object Model
     */
    public function update(Model $object)
    {
        try {
            if (!empty($this->items)) {
                foreach ($this->items as $key=>$item) {
                    if (is_array($item)) {
                        if ($item['id'] === $object->id) {
                            unset($item[$key]);
                            $this->addArray($object->toArray());
                        }
                    } else {
                        if ($item->id === $object->id) {
                            unset($item[$key]);
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