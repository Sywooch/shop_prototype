<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\EmailsModel;

/**
 * Создает объекты на оснований данных БД
 */
class EmailsObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
