<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\ProductsColorsModel;

/**
 * Создает объекты на оснований данных БД
 */
class ProductsColorsObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new ProductsColorsModel(['scenario'=>ProductsColorsModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
