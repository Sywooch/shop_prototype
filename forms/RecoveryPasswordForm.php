<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\UserEmailExistsAuthValidator;

/**
 * Представляет данные формы восстановления пароля
 */
class RecoveryPasswordForm extends AbstractBaseForm
{
    /**
     * Сценарий получения данных для восстановления
     */
    const GET = 'get';
    
    /**
     * @var string email для которого нужно восстановить пароль
     */
    public $email;
    
    public function scenarios()
    {
        return [
            self::GET=>['email'],
        ];
    }
    
    public function rules()
    {
        return [
            [['email'], 'required', 'on'=>self::GET],
            [['email'], 'email'],
            [['email'], UserEmailExistsAuthValidator::class, 'on'=>self::GET],
        ];
    }
}
