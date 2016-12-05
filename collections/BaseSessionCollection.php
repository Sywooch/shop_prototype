<?php

namespace app\collections;

use yii\base\ErrorException;
use app\collections\BaseCollection;

/**
 * Реализует интерфейс доступа к коллекции объектов, 
 * полученной из сессионного хранилища
 */
class BaseSessionCollection extends BaseCollection
{
    /**
     * Возвращает массив данных из коллекции
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
