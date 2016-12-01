<?php

namespace app\collections;

use yii\base\ErrorException;
use app\collections\BaseCollection;
use app\exceptions\ExceptionsTrait;
use app\models\CurrencyModel;

/**
 * Реализует интерфейс доступа к данным коллекции сущностей
 */
class CurrencySessionCollection extends BaseCollection
{
    /**
     * Получает 1 объект Model и добавляет его в коллекцию
     */
    public function getModel()
    {
        try {
            return !empty($this->items) ? new CurrencyModel($this->items[0]) : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает 1 массив данных из коллекции
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
