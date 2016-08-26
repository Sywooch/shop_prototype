<?php

namespace app\models;

use yii\db\Transaction;
use app\models\AbstractBaseModel;
use app\helpers\MappersHelper;

/**
 * Представляет данные таблицы currency
 */
class CurrencyModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    /**
     * Сценарий загрузки данных из формы, для установки текущей валюты
    */
    const GET_FOR_SET_CURRENCY = 'getForSetCurrency';
    /**
     * Сценарий загрузки данных из формы добавления CurrencyModel в БД
    */
    const GET_FOR_ADD = 'getForAdd';
    
    public $id;
    public $currency;
    public $exchange_rate;
    public $main;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'currency', 'exchange_rate', 'main'],
            self::GET_FOR_SET_CURRENCY=>['id'],
            self::GET_FOR_ADD=>['currency', 'exchange_rate', 'main'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::GET_FOR_SET_CURRENCY],
            [['currency', 'exchange_rate'], 'required', 'on'=>self::GET_FOR_ADD],
            [['currency'], 'app\validators\CurrencyTruncValidator', 'on'=>self::GET_FOR_ADD],
            [['currency'], 'app\validators\CurrencyCurrencyExistsValidator', 'on'=>self::GET_FOR_ADD],
            [['currency'], 'app\validators\StripTagsValidator'],
        ];
    }
    
    /**
     * Возвращает массив данных для сохранения в сессии
     * @return array
     */
    public function getDataArray()
    {
        try {
            return ['id'=>$this->id, 'currency'=>$this->currency, 'exchange_rate'=>$this->exchange_rate, 'main'=>$this->main];
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
