<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\UsersRulesModel;

/**
 * Создает объекты на оснований данных
 */
class UsersRulesObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new UsersRulesModel(['scenario'=>UsersRulesModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
