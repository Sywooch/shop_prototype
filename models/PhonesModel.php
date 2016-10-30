<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы phones
 */
class PhonesModel extends AbstractBaseModel
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
            return 'phones';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_ORDER=>['phone'],
        ];
    }
    
    public function rules()
    {
        return [
            [['phone'], 'app\validators\StripTagsValidator'],
            [['phone'], 'required', 'on'=>self::GET_FROM_ORDER],
        ];
    }
    
    public function fields()
    {
        return [
            'phone'=>'phone',
        ];
    }
}
