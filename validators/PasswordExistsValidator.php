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
    
    /**
     * @var array список полей, которые необходимо получит из БД
     */
    public static $_filedsFromDb = ['id', 'login', 'password', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'];
    
    private static $_passwordMessage = 'Неверный пароль!';
    
    /**
     * Проверяет идентичность введенного и хранящегося в БД пароля для существующей записи
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            if (empty(\Yii::$app->params['userFromFormForAuthentication'])) {
                $usersByLoginMapper = new UsersByLoginMapper([
                    'tableName'=>'users',
                    'fields'=>self::$_filedsFromDb,
                    'model'=>$model
                ]);
                \Yii::$app->params['userFromFormForAuthentication'] = $usersByLoginMapper->getOneFromGroup();
            }
            
            if (!is_object(\Yii::$app->params['userFromFormForAuthentication']) || !\Yii::$app->params['userFromFormForAuthentication'] instanceof UsersModel || !password_verify($model->rawPassword, \Yii::$app->params['userFromFormForAuthentication']->password)) {
                $this->addError($model, $attribute, self::$_passwordMessage);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
