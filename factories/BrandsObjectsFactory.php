<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\BrandsModel;

/**
 * Создает объекты на оснований данных БД
 */
class BrandsObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
