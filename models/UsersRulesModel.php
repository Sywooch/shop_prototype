<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы users_rules
 */
class UsersRulesModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    
    public $id_users;
    public $id_rules;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id_users', 'id_rules'],
        ];
    }
}
