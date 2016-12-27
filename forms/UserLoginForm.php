<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

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
        ];
    }
}
