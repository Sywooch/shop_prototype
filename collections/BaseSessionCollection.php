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
            return !empty($this->items) ? $this->items[0] : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
