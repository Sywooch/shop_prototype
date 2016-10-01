<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы mailing_list
 */
class MailingListModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы регистрации
    */
    const GET_FROM_REGISTRATION = 'getFromRegistration';
    
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'mailing_list';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'description'],
            self::GET_FROM_FORM=>['id', 'name', 'description'],
            self::GET_FROM_REGISTRATION=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'description'], 'app\validators\StripTagsValidator'],
        ];
    }
}
