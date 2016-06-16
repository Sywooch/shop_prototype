<?php

namespace app\factories;

use app\factories\AbstractGetOneFactory;
use app\models\UsersEmailsModel;

/**
 * Создает объекты на оснований данных
 */
class UsersEmailsOneObjectFactory extends AbstractGetOneFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new UsersEmailsModel(['scenario'=>UsersEmailsModel::GET_FROM_FORM]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
