<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\SizesModel;

/**
 * Создает объекты на оснований данных БД
 */
class SizesObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new SizesModel(['scenario'=>SizesModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
