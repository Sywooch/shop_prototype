<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\models\CurrencyInterface;

/**
 * Представляет данные таблицы currency
 */
class CurrencyModel extends AbstractBaseModel implements CurrencyInterface
{
    /**
     * Сценарий загрузки данных из СУБД
     */
    const DBMS = 'dbms';
    
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
            self::DBMS=>['id', 'code', 'exchange_rate', 'main'],
        ];
    }
    
    public function rules()
    {
        return [
            [['code'], 'app\validators\StripTagsValidator'],
        ];
    }
    
    /**
     * Возвращает курс текущей валюты
     */
    public function exchangeRate()
    {
        try {
            return $this->exchange_rate;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает код текущей валюты
     */
    public function code()
    {
        try {
            return $this->code;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
