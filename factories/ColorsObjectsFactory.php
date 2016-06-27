<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\ColorsModel;

/**
 * Создает объекты на оснований данных БД
 */
class ColorsObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
