<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;

/**
 * Проверяет валидность данных для формы UserRegistrationForm
 */
class PasswordIdenticRegValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет идентичность переданных пользователем паролей
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $password1 = $model->password;
            
            if ((string) $model->password !== (string) $model->$attribute) {
                $this->addError($model, $attribute, \Yii::t('base', 'Passwords do not match!'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
