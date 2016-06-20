<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\AddressModel;

/**
 * Создает объекты на оснований данных БД
 */
class AddressObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new AddressModel(['scenario'=>AddressModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
