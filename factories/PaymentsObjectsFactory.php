<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\PaymentsModel;

/**
 * Создает объекты на оснований данных БД
 */
class PaymentsObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new PaymentsModel(['scenario'=>PaymentsModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
