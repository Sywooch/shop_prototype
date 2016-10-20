<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;

/**
 * Назначает свойству ProductsModel::date дату 
 * создания записи в формате UNIX TIMESTAMP
 */
class ProductDateValidator extends Validator
{
    use ExceptionsTrait;
    
    public function validateAttribute($model, $attribute)
    {
        try {
            if (empty($model->$attribute)) {
                $model->$attribute = time();
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
