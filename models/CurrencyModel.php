<?php

namespace app\models;

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
    
    /**
     * Свойства содержат данные для редиректа после обработки запроса
     */
    public $categories; #!!!REPLACE
    public $subcategory; #!!!REPLACE
    public $search; #!!!REPLACE
    public $id_products; #!!!REPLACE
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'currency', 'exchange_rate', 'main'],
            self::GET_FOR_SET_CURRENCY=>['id', 'id_products', 'categories', 'subcategory', 'search'],
            self::GET_FOR_ADD=>['currency', 'exchange_rate', 'main'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::GET_FOR_SET_CURRENCY],
            [['currency', 'exchange_rate'], 'required', 'on'=>self::GET_FOR_ADD],
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
    
    /**
     * Присваивает значение свойству $this->_main
     * Так как в системе может быть только 1 главная валюта,
     * в случае выбора новой главной, обнуляется текущее значение main,
     * значение свойства exchange_rate принудительно выставляется в значение 1, 
     * значения остальных валют пересчитываются
     */
    public function setMain($value) #!!!TEST
    {
        try {
            if (!empty($value)) {
                if (!MappersHelper::setCurrencyUpdateMainNull()) {
                    throw new ErrorException('Ошибка при обнулении!');
                }
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
