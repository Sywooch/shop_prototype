<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\ProductsModel;

/**
 * Создает объекты на оснований данных БД
 */
class ProductsObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new ProductsModel(['scenario'=>ProductsModel::GET_LIST_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
