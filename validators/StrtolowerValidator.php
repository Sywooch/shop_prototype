<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class StrtolowerValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Переводит символы строки в нижний регистр
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            if (is_string($model->$attribute)) {
                $model->$attribute = mb_strtolower($model->$attribute);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
