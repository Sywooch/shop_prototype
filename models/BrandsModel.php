<?php

namespace app\models;

use yii\base\Model;

/**
 * ПРедставляет данные таблицы currency
 */
class BrandsModel extends Model
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    
    public $id;
    public $brand;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'brand'],
        ];
    }
}
