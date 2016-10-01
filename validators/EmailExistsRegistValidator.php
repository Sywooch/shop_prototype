<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\models\EmailsModel;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class EmailExistsRegistValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет, существует ли учетная запись с таким логином
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $emailsQuery = EmailsModel::find();
            $emailsQuery->innerJoin('users', '[[emails.id]]=[[users.id_email]]');
            $emailsQuery->where(['emails.email'=>$model->$attribute]);
            $result = $emailsQuery->exists();
            
            if ($result) {
                $this->addError($model, $attribute, \Yii::t('base', 'Account with this email already exist!'));
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
