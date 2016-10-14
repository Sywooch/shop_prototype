<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\models\EmailsModel;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class EmailExistsCreateValidator extends Validator
{
    use ExceptionsTrait;
    
    private $_errorText = 'This email is already registered!';
    
    /**
     * Проверяет, существует ли текущий email в БД
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $emailsQuery = EmailsModel::find();
            $emailsQuery->where(['emails.email'=>$model->$attribute]);
            $result = $emailsQuery->exists();
            
            if ($result) {
                $this->addError($model, $attribute, \Yii::t('base', $this->_errorText));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет, существует ли текущий email в БД, 
     * вызывается вне контекста модели
     * @param mixed $value значение, которое должно быть проверено
     * @param string $trror сообщение об ошибке, которое будет возвращено, если проверка провалится
     */
    public function validate($value, &$trror=null)
    {
        try {
            $emailsQuery = EmailsModel::find();
            $emailsQuery->where(['emails.email'=>$value]);
            $result = $emailsQuery->exists();
            
            if ($result) {
                return true;
            }
            return false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
