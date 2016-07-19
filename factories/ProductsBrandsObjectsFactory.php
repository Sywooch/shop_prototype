<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\ProductsBrandsModel;

/**
 * Создает объекты на оснований данных БД
 */
class ProductsBrandsObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new ProductsBrandsModel(['scenario'=>ProductsBrandsModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
