<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\EmailsMailingListModel;

/**
 * Создает объекты на оснований данных БД
 */
class EmailsMailingListObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new EmailsMailingListModel(['scenario'=>EmailsMailingListModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
