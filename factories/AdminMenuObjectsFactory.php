<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\AdminMenuModel;

/**
 * Создает объекты на оснований данных БД
 */
class AdminMenuObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new AdminMenuModel(['scenario'=>AdminMenuModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
