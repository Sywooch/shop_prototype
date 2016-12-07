<?php

namespace app\collections;

use yii\base\ErrorException;
use app\collections\BaseSessionCollection;
use app\models\CurrencyModel;

/**
 * Реализует интерфейс доступа к объекту текущей валюты, 
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
            if ($this->isEmpty()) {
                throw new ErrorException($this->emptyError('items'));
            }
            if ($this->isArrays() === false) {
                throw new ErrorException($this->invalidError('items'));
            }
            
            return new CurrencyModel($this->items[0]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
