<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\UsersModel;

/**
 * Создает объекты на оснований данных БД
 */
class UsersObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new UsersModel(['scenario'=>UsersModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
