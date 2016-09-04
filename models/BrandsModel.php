<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы currency
 */
class BrandsModel extends AbstractBaseModel
{
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'brands';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'brand'],
            self::GET_FROM_FORM=>['id', 'brand'],
        ];
    }
}
