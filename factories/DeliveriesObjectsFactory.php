<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\DeliveriesModel;

/**
 * Создает объекты на оснований данных БД
 */
class DeliveriesObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new DeliveriesModel(['scenario'=>DeliveriesModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
