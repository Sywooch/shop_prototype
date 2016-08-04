<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы mailing_list
 */
class MailingListModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    
    public $id;
    public $name;
    public $description;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'description'],
        ];
    }
}
