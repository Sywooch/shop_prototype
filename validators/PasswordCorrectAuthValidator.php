<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\UserEmailFinder;

/**
 * Проверяет валидность данных для формы UserLoginForm
 */
class PasswordCorrectAuthValidator extends Validator
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
            $finder = \Yii::$app->registry->get(UserEmailFinder::class, ['email'=>$model->email]);
            $usersModel = $finder->find();
            
            if (password_verify($model->$attribute, $usersModel->password) === false) {
                $this->addError($model, $attribute, \Yii::t('base', 'Password incorrect!'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
