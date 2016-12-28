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
    const GET = 'get';
    
    /**
     * @var string email пользователя
     */
    public $email;
    /**
     * @var string пароль
     */
    public $password;
    
    public function scenarios()
    {
        return [
            self::GET=>['email', 'password'],
        ];
    }
    
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            [['email'], UserEmailExistsAuthValidator::class, 'on'=>self::GET],
            [['password'], PasswordCorrectAuthValidator::class, 'on'=>self::GET, 'when'=>function($model, $attribute) {
                return empty($this->errors);
            }],
        ];
    }
}
