<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\ProductsSizesModel;

/**
 * Создает объекты на оснований данных БД
 */
class ProductsSizesObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new ProductsSizesModel(['scenario'=>ProductsSizesModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
