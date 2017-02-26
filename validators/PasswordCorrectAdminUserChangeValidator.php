<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\UserIdFinder;

/**
 * Проверяет валидность данных для формы UserChangePasswordForm
 */
class PasswordCorrectAdminUserChangeValidator extends Validator
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
            $finder = \Yii::$app->registry->get(UserIdFinder::class, [
                'id'=>$model->id
            ]);
            $usersModel = $finder->find();
            if (empty($usersModel)) {
                throw new ErrorException($this->emptyError('usersModel'));
            }
            
            if (password_verify($model->$attribute, $usersModel->password) === false) {
                $this->addError($model, $attribute, \Yii::t('base', 'Password incorrect!'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
