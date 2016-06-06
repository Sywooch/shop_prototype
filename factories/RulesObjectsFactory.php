<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\RulesModel;

/**
 * Создает объекты на оснований данных БД
 */
class RulesObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new RulesModel(['scenario'=>RulesModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
