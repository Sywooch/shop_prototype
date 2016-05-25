<?php

namespace app\models;

use yii\base\Model;

/**
 * Представляет данные таблицы categories
 */
class CategoriesModel extends Model
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    
    public $id;
    public $name;
    private $_subcategory = false;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name'],
        ];
    }
}
