<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы payments
 */
class PaymentsModel extends AbstractBaseModel
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
            return 'payments';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_ORDER=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'description'], 'app\validators\StripTagsValidator'],
            [['id'], 'required', 'on'=>self::GET_FROM_ORDER],
        ];
    }
}
