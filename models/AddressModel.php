<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\mappers\AddressByAddressMapper;
use app\mappers\AddressInsertMapper;
use yii\base\ErrorException;

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
    
    public $address = '';
    public $city = '';
    public $country = '';
    public $postcode = '';
    
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
     * Присваивает значение свойству $this->_id
     * @param string $value значение ID
     * @return boolean
     */
    public function setId($value)
    {
        try {
            if (is_numeric($value)) {
                $this->_id = $value;
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_id
     * @return boolean/int
     */
    public function getId()
    {
        try {
            if (is_null($this->_id)) {
                if (!empty($this->address) && !empty($this->city) && !empty($this->country)) {
                    $addressByAddressMapper = new AddressByAddressMapper([
                        'tableName'=>'address',
                        'fields'=>['id', 'address', 'city', 'country', 'postcode'],
                        'model'=>$this,
                    ]);
                    $addressModel = $addressByAddressMapper->getOneFromGroup();
                    if (!is_object($addressModel) || !$addressModel instanceof $this) {
                        return NULL;
                    }
                    $this->_id = $addressModel->id;
                }
            }
            return $this->_id;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных для сохранения в сессии
     * @return array
     */
    public function getDataArray()
    {
        try {
            return ['address'=>$this->address, 'city'=>$this->city, 'country'=>$this->country, 'postcode'=>$this->postcode];
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
