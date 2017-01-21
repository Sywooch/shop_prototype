<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;

/**
 * Проверяет валидность данных для формы UserChangePasswordForm
 */
class PasswordCorrectChangeValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет корректность переданного пользователем пароля
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $user = \Yii::$app->user->identity;
            
            if (password_verify($model->$attribute, $user->password) === false) {
                $this->addError($model, $attribute, \Yii::t('base', 'Password incorrect!'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
