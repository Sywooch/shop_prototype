<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use yii\base\Model;

/**
 * Проверяет пуст ли массив
 */
class ModelsArrayValidator extends Validator
{
    use ExceptionsTrait;
    
    public function validateAttribute($model, $attribute)
    {
        try {
            foreach ($model->$attribute as $item) {
                if (!$item instanceof Model) {
                    $this->addError($model, $attribute, \Yii::t('base/errors', 'Array {placeholder} must contain objects', ['placeholder'=>$attribute]));
                    break;
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
