<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * ПРедставляет данные таблицы currency
 */
class ColorsModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    
    public $id;
    public $color;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'color'],
        ];
    }
}
