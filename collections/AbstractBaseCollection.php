<?php

namespace app\collections;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;
use app\collections\AbstractIterator;

/**
 * Реализует интерфейс доступа к коллекции объектов
 */
class AbstractBaseCollection extends AbstractIterator
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
}
