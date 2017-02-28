<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;

/**
 * Приводит тип данных к integer
 */
class ActiveStatusTypeValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Приводит значение аттрибута к типу integer
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            if ($model->$attribute === (string) ACTIVE_STATUS || $model->$attribute === (string) INACTIVE_STATUS) {
                settype($model->$attribute, 'integer');
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
