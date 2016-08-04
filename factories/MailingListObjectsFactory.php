<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\MailingListModel;

/**
 * Создает объекты на оснований данных БД
 */
class MailingListObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
