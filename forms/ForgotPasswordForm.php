<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{UserEmailExistsAuthValidator,
    PasswordCorrectAuthValidator};

/**
 * Представляет данные формы восстановления пароля
 */
class ForgotPasswordForm extends AbstractBaseForm
{
    /**
     * Сценарий получения данных для восстановления
     */
    const GET = 'login';
    
}
