<?php

namespace app\factories;

use app\factories\AbstractGetOneFactory;
use app\models\EmailsModel;

/**
 * Создает объекты на оснований данных БД
 */
class EmailsOneObjectFactory extends AbstractGetOneFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
