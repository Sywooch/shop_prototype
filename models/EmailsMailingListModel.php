<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы emails_mailing_list
 */
class EmailsMailingListModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    
    public $id_email;
    public $id_mailing_list;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id_email', 'id_mailing_list'],
        ];
    }
}
