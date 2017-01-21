<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{PasswordCorrectChangeValidator,
    PasswordIdenticRegValidator};

/**
 * Представляет данные формы замены пароля
 */
class UserChangePasswordForm extends AbstractBaseForm
{
    /**
     * Сценарий получения данных для замены пароля
     */
    const CHANGE = 'change';
    
    /**
     * @var string текущий пароль
     */
    public $currentPassword;
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
            self::CHANGE=>['currentPassword', 'password', 'password2'],
        ];
    }
    
    public function rules()
    {
        return [
            [['currentPassword', 'password', 'password2'], 'required', 'on'=>self::CHANGE],
            [['currentPassword'], PasswordCorrectChangeValidator::class, 'on'=>self::CHANGE],
            [['password2'], PasswordIdenticRegValidator::class, 'on'=>self::CHANGE],
        ];
    }
}
