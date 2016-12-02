<?php

namespace app\collections;

use yii\base\ErrorException;
use app\collections\BaseSessionCollection;
use app\models\CurrencyModel;

/**
 * Реализует интерфейс доступа к объекту валюты, 
 * полученному из сессионного хранилища
 */
class CurrencySessionCollection extends BaseSessionCollection
{
    /**
     * Получает объект Model из коллекции
     */
    public function getModel()
    {
        try {
            return !empty($this->items) ? new CurrencyModel($this->items[0]) : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
