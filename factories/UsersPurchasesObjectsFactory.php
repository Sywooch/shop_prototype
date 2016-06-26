<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\UsersPurchasesModel;

/**
 * Создает объекты на оснований данных БД
 */
class UsersPurchasesObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new UsersPurchasesModel(['scenario'=>UsersPurchasesModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
