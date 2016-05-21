<?php

namespace app\factories;

use app\factories\AbstractBaseFactory;
use app\models\ProductModel;

class ProductObjectsFactory extends AbstractBaseFactory
{
    public function getObjects(Array $DbArray)
    {
        foreach ($DbArray as $entry) {
            $model = new ProductModel(['scenario'=>ProductModel::GET_FROM_DB]);
            $model->attributes = $entry;
            $this->$objectsArray[] = $model;
        }
    }
}
