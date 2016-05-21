<?php

namespace app\models;

use yii\base\Model;

class ProductModel extends Model
{
    const GET_FROM_DB = 'getFromBd';
    
    public $id;
    public $name;
    public $price;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'price'],
        ];
    }
}
