<?php

namespace app\models;

use yii\base\Model;

/**
 * ПРедставляет данные таблицы currency
 */
class CurrencyModel extends Model
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
