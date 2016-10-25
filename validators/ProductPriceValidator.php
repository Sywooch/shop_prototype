<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;

/**
 * Нормализует значение свойства ProductsModel::price
 */
class ProductPriceValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Удаляет пробелы, заменяет запятую точкой, 
     * приводит к денежному фрмату 0.00
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $priceString = str_replace([',', ' '], ['.', ''], $model->$attribute);
            
            if (!is_numeric($priceString)) {
                $this->addError($model, $attribute, \Yii::t('base/errors', 'Wrong format!'));
            } else {
                $model->$attribute = number_format((float) $priceString, 2, '.', '');
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
