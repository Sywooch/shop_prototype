<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы users_rules
 */
class UsersAddressModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    /**
     * Сценарий загрузки данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    
    public $id_users;
    public $id_address;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id_users', 'id_address'],
            self::GET_FROM_FORM=>['id_users', 'id_address'],
        ];
    }
}
