<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\PurchasesModel;

/**
 * Создает объекты на оснований данных БД
 */
class PurchasesObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
