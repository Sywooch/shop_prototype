<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\SubcategoryModel;

/**
 * Создает объекты на оснований данных БД
 */
class SubcategoryObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
