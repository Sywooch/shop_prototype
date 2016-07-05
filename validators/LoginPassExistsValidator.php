<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;
use app\mappers\UsersByLoginMapper;
use app\models\UsersModel;

/**
 * Проверяет атрибуты модели UsersModel
 */
class LoginPassExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    private static $_loginMessage = 'Пользователя с таким логином не существует!';
    private static $_passwordMessage = 'Неверный пароль!';
    
    /**
     * Проверяет, существует ли учетная запись с таким логином,
     * проверяет пароль
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $usersByLoginMapper = new UsersByLoginMapper([
                'tableName'=>'users',
                'fields'=>['id'],
                'model'=>$model
            ]);
            $usersModel = $usersByLoginMapper->getOneFromGroup();
            
            if ($model->scenario == UsersModel::GET_FROM_LOGIN_FORM) {
                if (!is_object($usersModel) && !$usersModel instanceof UsersModel) {
                    $this->addError($model, 'login', self::$_loginMessage);
                } else {
                    if (!password_verify($model->password, $usersModel->password)) {
                        $this->addError($model, 'password', self::$_passwordMessage);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
