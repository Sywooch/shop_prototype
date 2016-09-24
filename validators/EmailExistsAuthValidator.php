<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\models\EmailsModel;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class EmailExistsAuthValidator extends Validator
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
            $result = EmailsModel::find()->where(['emails.email'=>$model->$attribute])->exists();
            
            if (!$result) {
                $this->addError($model, $attribute, \Yii::t('base', 'Account with this email does not exist!'));
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
