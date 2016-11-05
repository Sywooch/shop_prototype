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
            if ($this->check($model->$attribute)) {
                $this->addError($model, $attribute, \Yii::t('base', 'This phone is already registered!'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет, существует ли текущий phone в БД, 
     * вызывается вне контекста модели
     * @param string $value телефон, который должен быть проверен
     * @param string $error сообщение об ошибке, которое будет возвращено, если проверка провалится
     * @return bool
     */
    public function validate($value, &$error=null): bool
    {
        try {
            return $this->check($value);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Проверят существование телефона
     * @param string $value телефон, который должен быть проверен
     * @return bool
     */
    private function check(string $value): bool
    {
        try {
            $phonesQuery = PhonesModel::find();
            $phonesQuery->where(['[[phones.phone]]'=>$value]);
            $result = $phonesQuery->exists();
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
