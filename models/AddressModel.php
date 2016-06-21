<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\mappers\AddressByAddressMapper;

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
        try {
            if (is_null($this->_id)) {
                if (isset($this->address, $this->city, $this->country, $this->postcode)) {
                    $addressByAddressMapper = new AddressByAddressMapper([
                        'tableName'=>'address',
                        'fields'=>['id', 'address', 'city', 'country', 'postcode'],
                        'model'=>$this,
                    ]);
                    if ($addressModel = $addressByAddressMapper->getOneFromGroup()) {
                        $this->_id = $addressModel->id;
                    }
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
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
