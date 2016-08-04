<?php

namespace app\models;

use app\models\AbstractBaseModel;
use yii\base\ErrorException;
use app\helpers\MappersHelper;

/**
 * Представляет данные таблицы currency
 */
class EmailsModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    /**
     * Сценарий загрузки данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    /**
     * Сценарий сохранения данных из формы регистрации
    */
    const GET_FROM_REGISTRATION_FORM = 'getFromRegistrationForm';
    /**
     * Сценарий сохранения данных из формы авторизации
    */
    const GET_FROM_LOGIN_FORM = 'getFromLoginForm';
    
    public $email;
    private $_id = null;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'email'],
            self::GET_FROM_FORM=>['email'],
            self::GET_FROM_REGISTRATION_FORM=>['email'],
            self::GET_FROM_LOGIN_FORM=>['email'],
        ];
    }
    
    public function rules()
    {
        return [
            [['email'], 'required', 'on'=>self::GET_FROM_FORM],
            [['email'], 'required', 'on'=>self::GET_FROM_REGISTRATION_FORM],
            [['email'], 'required', 'on'=>self::GET_FROM_LOGIN_FORM],
            [['email'], 'email'],
            ['email', 'app\validators\EmailExistsValidator'],
        ];
    }
    
    /**
     * Присваивает значение свойству $this->_id
     * @param string/int $value значение ID
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
                if (!empty($this->email)) {
                    $emailsModel = MappersHelper::getEmailsByEmail($this);
                    if (!is_object($emailsModel) || !$emailsModel instanceof $this) {
                        return null;
                    }
                    $this->_id = $emailsModel->id;
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
            return ['email'=>$this->email];
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
