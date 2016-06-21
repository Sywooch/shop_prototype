<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\UsersPhonesModel;

/**
 * Создает объекты на оснований данных
 */
class UsersPhonesObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new UsersPhonesModel(['scenario'=>UsersPhonesModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
