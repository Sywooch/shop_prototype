<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class CurrencyTruncValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Урезает аббревиатуру валюты до 3-х символов
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $model->$attribute = substr($model->$attribute, 0, 3);
            $model->$attribute = mb_strtoupper($model->$attribute);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
