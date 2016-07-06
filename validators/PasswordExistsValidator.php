<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;
use app\mappers\UsersByLoginMapper;
use app\models\UsersModel;

/**
 * Проверяет атрибуты модели UsersModel
 */
class PasswordExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    private static $_passwordMessage = 'Неверный пароль!';
    
    /**
     * Проверяет идентичность введенного и хранящегося в БД пароля для существующей записи
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $usersByLoginMapper = new UsersByLoginMapper([
                'tableName'=>'users',
                'fields'=>['password'],
                'model'=>$model
            ]);
            $usersModel = $usersByLoginMapper->getOneFromGroup();
            
            if (!is_object($usersModel) || !$usersModel instanceof UsersModel || !password_verify($model->rawPassword, $usersModel->password)) {
                $this->addError($model, $attribute, self::$_passwordMessage);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
