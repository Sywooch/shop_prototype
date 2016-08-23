<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\AbstractBaseModel;
use app\helpers\MappersHelper;

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
     * Сценарий загрузки данных из БД
     */
    const GET_FROM_DB = 'getFromDb';
    /**
     * Сценарий загрузки данных из формы обновления данных
     */
    const GET_FOR_UPDATE = 'getForUpdate';
    
    public $address;
    public $city;
    public $country;
    public $postcode;
    
    private $_id = null;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['address', 'city', 'country', 'postcode'],
            self::GET_FROM_DB=>['id', 'address', 'city', 'country', 'postcode'],
            self::GET_FOR_UPDATE=>['address', 'city', 'country', 'postcode'],
        ];
    }
    
    public function rules()
    {
        return [
            [['address', 'city', 'country'], 'required', 'on'=>self::GET_FROM_FORM],
            [['address', 'city', 'country', 'postcode'], 'app\validators\StripTagsValidator'],
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
                if (!empty($this->address) || !empty($this->city) || !empty($this->country) || !empty($this->postcode)) {
                    $addressModel = MappersHelper::getAddressByAddress($this);
                    if (!is_object($addressModel) || !$addressModel instanceof $this) {
                        return null;
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
