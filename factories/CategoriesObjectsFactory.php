<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\CategoriesModel;

/**
 * Создает объекты на оснований данных БД
 */
class CategoriesObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
