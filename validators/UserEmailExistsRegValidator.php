<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\UserEmailFinder;

/**
 * Проверяет валидность данных для формы UserRegistrationForm
 */
class UserEmailExistsRegValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет, существует ли учетная запись с указанным email, 
     * фиксирует ошибку, если результат проверки положителен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = \Yii::$app->registry->get(UserEmailFinder::class, ['email'=>$model->$attribute]);
            $result = $finder->find();
            
            if ($result !== null) {
                $this->addError($model, $attribute, \Yii::t('base', 'Account with this email already exist!'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
