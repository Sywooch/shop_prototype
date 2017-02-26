<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{PasswordCorrectChangeValidator,
    PasswordCorrectAdminUserChangeValidator,
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
     * Сценарий обновления данных пользователя через админ раздел
     */
    const ADMIN_UPDATE = 'admin_update';
    
    /**
     * @var int id покупателя
     */
    public $id;
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
            self::ADMIN_UPDATE=>['id', 'currentPassword', 'password', 'password2'],
        ];
    }
    
    public function rules()
    {
        return [
            [['currentPassword', 'password', 'password2'], 'required', 'on'=>self::CHANGE],
            [['id', 'currentPassword', 'password', 'password2'], 'required', 'on'=>self::ADMIN_UPDATE],
            [['currentPassword'], PasswordCorrectChangeValidator::class, 'on'=>self::CHANGE],
            [['currentPassword'], PasswordCorrectAdminUserChangeValidator::class, 'on'=>self::ADMIN_UPDATE],
            [['password2'], PasswordIdenticRegValidator::class],
        ];
    }
}
