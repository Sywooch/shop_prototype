<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\ProductsModel;

/**
 * Создает объекты на оснований данных БД
 */
class ProductDetailObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new ProductsModel(['scenario'=>ProductsModel::GET_LIST_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
