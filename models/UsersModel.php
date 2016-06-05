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
    public $password;
    public $name;
    
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
}
