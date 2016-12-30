<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{UserEmailExistsAuthValidator,
    PasswordCorrectAuthValidator};

/**
 * Представляет данные формы аутентификации пользователя
 */
class UserLoginForm extends AbstractBaseForm
{
    /**
     * Сценарий получения данных для аутентификации
     */
    const LOGIN = 'login';
    /**
     * Сценарий обнуления данных аутентификации
     */
    const LOGOUT = 'logout';
    
    /**
     * @var string email пользователя
     */
    public $email;
    /**
     * @var string пароль
     */
    public $password;
    /**
     * @var int Id пользователя
     */
    public $id;
    
    public function scenarios()
    {
        return [
            self::LOGIN=>['email', 'password'],
            self::LOGOUT=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['email', 'password'], 'required', 'on'=>self::LOGIN],
            [['email'], 'email', 'on'=>self::LOGIN],
            [['email'], UserEmailExistsAuthValidator::class, 'on'=>self::LOGIN],
            [['password'], PasswordCorrectAuthValidator::class, 'on'=>self::LOGIN, 'when'=>function($model, $attribute) {
                return empty($this->errors);
            }],
            [['id'], 'required', 'on'=>self::LOGOUT],
        ];
    }
}
