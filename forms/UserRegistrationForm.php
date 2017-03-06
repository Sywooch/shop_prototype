<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{PasswordIdenticRegValidator,
    UserEmailExistsRegValidator,
    StripTagsValidator};

/**
 * Представляет данные формы аутентификации пользователя
 */
class UserRegistrationForm extends AbstractBaseForm
{
    /**
     * Сценарий получения данных для аутентификации
     */
    const REGISTRATION = 'registration';
    
    /**
     * @var string email пользователя
     */
    public $email;
    /**
     * @var string пароль
     */
    public $password;
    /**
     * @var string подтверждение пароля
     */
    public $password2;
    
    public function scenarios()
    {
        return [
            self::REGISTRATION=>['email', 'password', 'password2'],
        ];
    }
    
    public function rules()
    {
        return [
            [['email', 'password', 'password2'], StripTagsValidator::class],
            [['email', 'password', 'password2'], 'required', 'on'=>self::REGISTRATION],
            [['email'], 'email', 'on'=>self::REGISTRATION],
            [['email'], UserEmailExistsRegValidator::class, 'on'=>self::REGISTRATION],
            [['password2'], PasswordIdenticRegValidator::class, 'on'=>self::REGISTRATION],
        ];
    }
}
