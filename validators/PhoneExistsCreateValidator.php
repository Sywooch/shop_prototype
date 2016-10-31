<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\models\PhonesModel;

/**
 * Проверяет атрибуты модели PhonesModel
 */
class PhoneExistsCreateValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет, существует ли текущий phone в БД
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $phonesQuery = PhonesModel::find();
            $phonesQuery->where(['[[phones.phone]]'=>$model->$attribute]);
            $result = $phonesQuery->exists();
            
            if ($result) {
                $this->addError($model, $attribute, \Yii::t('base', 'This phone is already registered!'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет, существует ли текущий phone в БД, 
     * вызывается вне контекста модели
     * @param mixed $value значение, которое должно быть проверено
     * @param string $error сообщение об ошибке, которое будет возвращено, если проверка провалится
     * @return bool
     */
    public function validate($value, &$error=null): bool
    {
        try {
            $phonesQuery = PhonesModel::find();
            $phonesQuery->where(['[[phones.phone]]'=>$value]);
            $result = $phonesQuery->exists();
            
            if ($result) {
                return true;
            }
            
            return false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
