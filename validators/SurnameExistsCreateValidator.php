<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\models\SurnamesModel;

/**
 * Проверяет атрибуты модели SurnamesModel
 */
class SurnameExistsCreateValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет, существует ли текущий surname в БД
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $surnamesQuery = SurnamesModel::find();
            $surnamesQuery->where(['[[surnames.surname]]'=>$model->$attribute]);
            $result = $surnamesQuery->exists();
            
            if ($result) {
                $this->addError($model, $attribute, \Yii::t('base', 'This surname is already exists in database!'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет, существует ли текущий surname в БД, 
     * вызывается вне контекста модели
     * @param mixed $value значение, которое должно быть проверено
     * @param string $error сообщение об ошибке, которое будет возвращено, если проверка провалится
     * @return bool
     */
    public function validate($value, &$error=null): bool
    {
        try {
            $surnamesQuery = SurnamesModel::find();
            $surnamesQuery->where(['[[surnames.surname]]'=>$value]);
            $result = $surnamesQuery->exists();
            
            if ($result) {
                return true;
            }
            
            return false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
