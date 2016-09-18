<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы phones
 */
class AddressModel extends AbstractBaseModel
{
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'address';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'address', 'city', 'country', 'postcode'],
            self::GET_FROM_FORM=>['id', 'address', 'city', 'country', 'postcode'],
        ];
    }
}
