<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\models\PostcodesModel;

/**
 * Проверяет атрибуты модели PostcodesModel
 */
class PostcodeExistsCreateValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет, существует ли текущий postcode в БД
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            if ($this->check($model->$attribute)) {
                $this->addError($model, $attribute, \Yii::t('base', 'This postcode is already exists in database!'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет, существует ли текущий postcode в БД, 
     * вызывается вне контекста модели
     * @param string $value postcode, который должен быть проверен
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
     * Проверят существование postcode
     * @param string $value postcode, который должен быть проверен
     * @return bool
     */
    private function check(string $value): bool
    {
        try {
            $postcodesQuery = PostcodesModel::find();
            $postcodesQuery->where(['[[postcodes.postcode]]'=>$value]);
            $result = $postcodesQuery->exists();
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
