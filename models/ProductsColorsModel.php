<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы products_colors
 */
class ProductsColorsModel extends AbstractBaseModel
{
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'products_colors';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id_product', 'id_color'],
            self::GET_FROM_FORM=>['id_product', 'id_color'],
        ];
    }
}
