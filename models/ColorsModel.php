<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы colors
 */
class ColorsModel extends AbstractBaseModel
{
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'colors';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'color'],
            self::GET_FROM_FORM=>['id', 'color'],
        ];
    }
}
