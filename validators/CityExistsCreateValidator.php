<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\models\CitiesModel;

/**
 * Проверяет атрибуты модели CitiesModel
 */
class CityExistsCreateValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет, существует ли текущий город в БД
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            if ($this->check($model->$attribute)) {
                $this->addError($model, $attribute, \Yii::t('base', 'This city is already exists in database!'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет, существует ли текущий город в БД, 
     * вызывается вне контекста модели
     * @param string $value город, который должен быть проверен
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
     * Проверят существование города
     * @param string $value город, который должен быть проверен
     * @return bool
     */
    private function check(string $value): bool
    {
        try {
            $citiesQuery = CitiesModel::find();
            $citiesQuery->where(['[[cities.city]]'=>$value]);
            $result = $citiesQuery->exists();
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
