<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{PasswordCorrectChangeValidator,
    PasswordCorrectAdminUserChangeValidator,
    PasswordIdenticRegValidator,
    StripTagsValidator};

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
     * @var string новый пароль
     */
    public $password;
    /**
     * @var string подтверждение нового пароля
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
            [['id', 'currentPassword', 'password', 'password2'], StripTagsValidator::class],
            [['currentPassword', 'password', 'password2'], 'required', 'on'=>self::CHANGE],
            [['id', 'currentPassword', 'password', 'password2'], 'required', 'on'=>self::ADMIN_UPDATE],
            [['id'], 'integer'],
            [['currentPassword', 'password', 'password2'], 'string'],
            [['currentPassword', 'password', 'password2'], 'match', 'pattern'=>'#^[^\s]+$#u'],
            [['currentPassword'], PasswordCorrectChangeValidator::class, 'on'=>self::CHANGE],
            [['currentPassword'], PasswordCorrectAdminUserChangeValidator::class, 'on'=>self::ADMIN_UPDATE],
            [['password2'], PasswordIdenticRegValidator::class],
        ];
    }
}
