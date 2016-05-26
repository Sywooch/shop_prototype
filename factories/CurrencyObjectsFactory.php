<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\CurrencyModel;

/**
 * Создает объекты на оснований данных БД
 */
class CurrencyObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
