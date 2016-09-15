<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы products_sizes
 */
class ProductsSizesModel extends AbstractBaseModel
{
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'products_sizes';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id_product', 'id_size'],
            self::GET_FROM_FORM=>['id_product', 'id_size'],
        ];
    }
}
