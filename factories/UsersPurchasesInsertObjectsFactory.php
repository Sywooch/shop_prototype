<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\UsersPurchasesModel;

/**
 * Создает объекты на оснований данных БД
 */
class UsersPurchasesInsertObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new UsersPurchasesModel(['scenario'=>UsersPurchasesModel::GET_FROM_FORM]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
