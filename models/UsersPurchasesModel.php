<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы users
 */
class UsersPurchasesModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    /**
     * Сценарий сохранения данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    
    public $id = '';
    public $id_users = '';
    public $id_products = '';
    public $id_deliveries = '';
    public $id_payments = '';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['id_users', 'id_products', 'id_deliveries', 'id_payments'],
            self::GET_FROM_DB=>['id', 'id_users', 'id_products', 'id_deliveries', 'id_payments'],
        ];
    }
}
