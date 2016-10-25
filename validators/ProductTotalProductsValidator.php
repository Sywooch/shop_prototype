<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;

/**
 * Нормализует значение свойства ProductsModel::total_products
 */
class ProductTotalProductsValidator extends Validator
{
    use ExceptionsTrait;
    
    public function validateAttribute($model, $attribute)
    {
        try {
            if (!is_numeric($model->$attribute)) {
                $this->addError($model, $attribute, \Yii::t('base/errors', 'Wrong format!'));
            } else {
                $model->$attribute = (int) $model->$attribute;
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
