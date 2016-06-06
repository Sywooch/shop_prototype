<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\mappers\RulesMapper;

/**
 * Представляет данные таблицы users
 */
class UsersModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    
    public $id;
    public $login;
    public $name;
    public $rulesFromForm = array();
    
    private $_password;
    private $_allRules = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['login', 'password', 'name'],
        ];
    }
    
    public function rules()
    {
        return [
            [['login', 'password'], 'required', 'on'=>self::GET_FROM_FORM],
            ['login', 'app\validators\ExistUserValidator', 'on'=>self::GET_FROM_FORM],
        ];
    }
    
    /**
     * Хэширует пароль перед присвоением значения свойству $this->_password
     * @param string $value значение пароля
     */
    public function setPassword($value)
    {
        try {
            $this->_password = password_hash($value, PASSWORD_DEFAULT);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_password
     */
    public function getPassword()
    {
        return $this->_password;
    }
    
    /**
     * Возвращает массив объектов всех доступных rules для формы создания пользователя
     * @return array
     */
    public function getAllRules()
    {
        try {
            if (is_null($this->_allRules)) {
                $rulesMapper = new RulesMapper([
                    'tableName'=>'rules',
                    'fields'=>['id', 'rule'],
                    'orderByField'=>'rule',
                ]);
                $this->_allRules = $rulesMapper->getGroup();
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_allRules;
    }
}
