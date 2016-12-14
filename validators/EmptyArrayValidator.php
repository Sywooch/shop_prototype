<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;

/**
 * Проверяет пуст ли массив
 */
class EmptyArrayValidator extends Validator
{
    use ExceptionsTrait;
    
    public function validateAttribute($model, $attribute)
    {
        try {
            if (empty($model->$attribute)) {
                $this->addError($model, $attribute, \Yii::t('base/errors', 'Array {placeholder} is empty', ['placeholder'=>$attribute]));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
