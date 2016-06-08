<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы sizes
 */
class SizesModel extends AbstractBaseModel
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
