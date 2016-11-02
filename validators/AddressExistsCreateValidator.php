<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\models\AddressModel;

/**
 * Проверяет атрибуты модели AddressModel
 */
class AddressExistsCreateValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет, существует ли текущий address в БД
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $addressQuery = AddressModel::find();
            $addressQuery->where(['[[address.address]]'=>$model->$attribute]);
            $result = $addressQuery->exists();
            
            if ($result) {
                $this->addError($model, $attribute, \Yii::t('base', 'This address is already exists in database!'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет, существует ли текущий address в БД, 
     * вызывается вне контекста модели
     * @param mixed $value значение, которое должно быть проверено
     * @param string $error сообщение об ошибке, которое будет возвращено, если проверка провалится
     * @return bool
     */
    public function validate($value, &$error=null): bool
    {
        try {
            $addressQuery = AddressModel::find();
            $addressQuery->where(['[[address.address]]'=>$value]);
            $result = $addressQuery->exists();
            
            if ($result) {
                return true;
            }
            
            return false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
