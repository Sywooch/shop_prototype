<?php

namespace app\models;

use yii\base\Model;

/**
 * Представляет данные таблицы products
 */
class ProductModel extends Model
{
    /**
     * Сценарий загрузки данных из БД в рамках списка продуктов
    */
    const GET_LIST_FROM_DB = 'getFromBd';
    
    public $id;
    public $code;
    public $name;
    public $description;
    public $price;
    public $images;
    public $id_categories;
    public $id_subcategory;
    public $colors;
    public $sizes;
    
    public function scenarios()
    {
        return [
            self::GET_LIST_FROM_DB=>['id', 'code', 'name', 'description', 'price', 'images'],
        ];
    }
}
