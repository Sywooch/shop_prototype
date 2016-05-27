<?php

namespace app\models;

use yii\base\Model;

/**
 * ПРедставляет данные таблицы sizes
 */
class SizesModel extends Model
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    
    public $id;
    public $size;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'size'],
        ];
    }
}
