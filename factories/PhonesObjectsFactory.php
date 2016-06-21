<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\PhonesModel;

/**
 * Создает объекты на оснований данных БД
 */
class PhonesObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
