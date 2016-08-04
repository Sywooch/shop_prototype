<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы subcategory
 */
class SubcategoryModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    
    public $id;
    public $name;
    public $seocode;
    public $id_categories;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'seocode', 'id_categories'],
        ];
    }
}
