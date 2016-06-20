<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы address
 */
class AddressModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из формы
     */
    const GET_FROM_FORM = 'getFromForm';
    /**
     * Сценарий загрузки данных из формы
     */
    const GET_FROM_DB = 'getFromDb';
    
    public $address;
    public $city;
    public $country;
    public $postcode;
    
    private $_id = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['address', 'city', 'country', 'postcode'],
            self::GET_FROM_DB=>['id', 'address', 'city', 'country', 'postcode'],
        ];
    }
    
    public function rules()
    {
        return [
            [['address', 'city', 'country'], 'required', 'on'=>self::GET_FROM_FORM],
        ];
    }
    
    /**
     * Возвращает значение свойства $this->_id
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Присваивает значение свойству $this->_id
     * @param string $value значение ID
     */
    public function setId($value)
    {
        $this->_id = $value;
    }
}
