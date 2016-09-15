<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы sizes
 */
class SizesModel extends AbstractBaseModel
{
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'sizes';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'size'],
            self::GET_FROM_FORM=>['id', 'size'],
        ];
    }
}
