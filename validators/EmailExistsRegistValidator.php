<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\models\UsersModel;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class EmailExistsRegistValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет, существует ли учетная запись с текущим email
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $usersQuery = UsersModel::find();
            $usersQuery->innerJoin('{{emails}}', '[[users.id_email]]=[[emails.id]]');
            $usersQuery->where(['[[emails.email]]'=>$model->$attribute]);
            $result = $usersQuery->exists();
            
            if ($result) {
                $this->addError($model, $attribute, \Yii::t('base', 'Account with this email already exist!'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
