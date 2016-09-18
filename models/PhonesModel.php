<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы phones
 */
class PhonesModel extends AbstractBaseModel
{
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'phones';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'phone'],
            self::GET_FROM_FORM=>['id', 'phone'],
        ];
    }
}
