<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\mappers\PhonesByPhoneMapper;

/**
 * Представляет данные таблицы phones
 */
class PhonesModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из формы
     */
    const GET_FROM_FORM = 'getFromForm';
    /**
     * Сценарий загрузки данных из БД
     */
    const GET_FROM_DB = 'getFromDb';
    
    public $phone;
    
    private $_id = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['phone'],
            self::GET_FROM_DB=>['id', 'phone'],
        ];
    }
    
    public function rules()
    {
        return [
            [['phone'], 'required', 'on'=>self::GET_FROM_FORM],
        ];
    }
    
    /**
     * Возвращает значение свойства $this->_id
     */
    public function getId()
    {
        if (is_null($this->_id)) {
            if (isset($this->phone)) {
                $phonesByPhoneMapper = new PhonesByPhoneMapper([
                    'tableName'=>'phones',
                    'fields'=>['id', 'phone'],
                    'model'=>$this
                ]);
                if ($objectPhones = $phonesByPhoneMapper->getOneFromGroup()) {
                    $this->_id = $objectPhones->id;
                }
            }
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
