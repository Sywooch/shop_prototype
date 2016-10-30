<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы phones
 */
class AddressModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы заказа
    */
    const GET_FROM_ORDER = 'getFromOrder';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'address';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_ORDER=>['address', 'city', 'country', 'postcode'],
        ];
    }
    
    public function rules()
    {
        return [
            [['address', 'city', 'country', 'postcode'], 'app\validators\StripTagsValidator'],
            [['address', 'city', 'country', 'postcode'], 'required', 'on'=>self::GET_FROM_ORDER],
        ];
    }
    
    public function fields()
    {
        return [
            'address'=>'address',
            'city'=>'city',
            'country'=>'country',
            'postcode'=>'postcode',
        ];
    }
}
