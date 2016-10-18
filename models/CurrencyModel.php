<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы currency
 */
class CurrencyModel extends AbstractBaseModel
{
    /**
     * Сценарий изменения текущей валюты
    */
    const GET_FROM_CHANGE_CURRENCY = 'getFromChangeCurrency';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'currency';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'code', 'exchange_rate', 'main'],
            self::GET_FROM_FORM=>['id', 'code', 'exchange_rate', 'main'],
            self::GET_FROM_CHANGE_CURRENCY=>['id', 'code'],
        ];
    }
    
    public function rules()
    {
        return [
            [['code'], 'app\validators\StripTagsValidator'],
            [['id'], 'required', 'on'=>self::GET_FROM_CHANGE_CURRENCY],
        ];
    }
}
