<?php

namespace app\models;

use app\models\AbstractBaseModel;

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
    
    public $id;
    public $currency;
    public $exchange_rate;
    public $main;
    
    /**
     * Свойства содержат данные для редиректа после обработки запроса
     */
    public $categories;
    public $subcategory;
    public $search;
    public $id_products;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'currency', 'exchange_rate', 'main'],
            self::GET_FOR_SET_CURRENCY=>['id', 'id_products', 'categories', 'subcategory', 'search'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::GET_FOR_SET_CURRENCY],
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
