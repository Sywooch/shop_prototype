<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\UsersAddressModel;

/**
 * Создает объекты на оснований данных
 */
class UsersAddressObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new UsersAddressModel(['scenario'=>UsersAddressModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
