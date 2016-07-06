<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;
use app\mappers\UsersByLoginMapper;
use app\models\UsersModel;

/**
 * Проверяет атрибуты модели UsersModel
 */
class LoginExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    private static $_registartionMessage = 'Пользователь с таким логином уже существует!';
    private static $_loginMessage = 'Пользователя с таким логином не существует!';
    
    /**
     * Проверяет, существует ли учетная запись с таким логином
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $usersByLoginMapper = new UsersByLoginMapper([
                'tableName'=>'users',
                'fields'=>['login'],
                'model'=>$model
            ]);
            $usersModel = $usersByLoginMapper->getOneFromGroup();
            
            if ($model->scenario == UsersModel::GET_FROM_REGISTRATION_FORM) {
                if (is_object($usersModel) && $usersModel instanceof UsersModel) {
                    $this->addError($model, $attribute, self::$_registartionMessage);
                }
            }
            
            if ($model->scenario == UsersModel::GET_FROM_LOGIN_FORM) {
                if (!is_object($usersModel) || !$usersModel instanceof UsersModel) {
                    $this->addError($model, $attribute, self::$_loginMessage);
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
