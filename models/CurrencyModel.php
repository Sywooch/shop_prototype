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
     * Сценарий обновления данных
     */
    const UPDATE = 'update';
    /**
     * Сценарий создания валюты
     */
    const CREATE = 'create';
    /**
     * Сценарий удаления валюты
     */
    const DELETE = 'delete';
    /**
     * Сценарий замены базовой валюты
     */
    const BASE_CHANGE = 'base_change';
    /**
     * Сценарий сохранения ID валюты в сесии
     */
    const SESSION = 'session';
    
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
            self::DBMS=>['id', 'code', 'exchange_rate', 'main', 'update_date'],
            self::UPDATE=>['id', 'exchange_rate', 'update_date'],
            self::CREATE=>['code', 'exchange_rate', 'main', 'update_date'],
            self::DELETE=>['id'],
            self::BASE_CHANGE=>['id', 'main', 'exchange_rate', 'update_date'],
            self::SESSION=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'exchange_rate', 'update_date'], 'required', 'on'=>self::UPDATE],
            [['code', 'exchange_rate', 'update_date'], 'required', 'on'=>self::CREATE],
            [['main'], 'default', 'value'=>0, 'on'=>self::CREATE],
            [['id'], 'required', 'on'=>self::DELETE],
            [['id', 'main', 'exchange_rate', 'update_date'], 'required', 'on'=>self::BASE_CHANGE],
            [['id'], 'required', 'on'=>self::SESSION],
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
