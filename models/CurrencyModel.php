<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * ПРедставляет данные таблицы currency
 */
class CurrencyModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    
    public $id;
    public $currency;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'currency'],
        ];
    }
}
