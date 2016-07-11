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
    const GET_FROM_FORM_SET = 'getFromFormSet';
    
    public $id = '';
    public $currency = '';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'currency'],
            self::GET_FROM_FORM_SET=>['id'],
        ];
    }
}
