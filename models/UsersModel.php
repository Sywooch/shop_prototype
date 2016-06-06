<?php

namespace app\models;

use yii\base\Model;

/**
 * Представляет данные таблицы users
 */
class UsersModel extends Model
{
    /**
     * Сценарий сохранения данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    
    public $id;
    public $login;
    public $name;
    
    private $_password;
    
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
        $this->_password = password_hash($value, PASSWORD_DEFAULT);
    }
    
    /**
     * Возвращает значение свойства $this->_password
     */
    public function getPassword()
    {
        return $this->_password;
    }
}
