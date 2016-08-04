<?php

namespace app\models;

use app\models\AbstractBaseModel;
use yii\base\ErrorException;
use app\helpers\MappersHelper;

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
    /**
     * Сценарий загрузки данных из формы обновления данных
     */
    const GET_FROM_UPDATE_FORM = 'getFromUpdateForm';
    
    public $phone;
    private $_id = null;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['phone'],
            self::GET_FROM_DB=>['id', 'phone'],
            self::GET_FROM_UPDATE_FORM=>['phone'],
        ];
    }
    
    public function rules()
    {
        return [
            [['phone'], 'required', 'on'=>self::GET_FROM_FORM],
            [['phone'], 'app\validators\StripTagsValidator'],
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
     * @return int
     */
    public function getId()
    {
        try {
            if (is_null($this->_id)) {
                if (!empty($this->phone)) {
                    $phonesModel = MappersHelper::getPhonesByPhone($this);
                    if (!is_object($phonesModel) || !$phonesModel instanceof $this) {
                        return null;
                    }
                    $this->_id = $phonesModel->id;
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
            return ['phone'=>$this->phone];
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
