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
    
    public function validateAttribute($model, $attribute)
    {
        try {
            $priceString = str_replace(',', '.', $model->$attribute);
            $priceFloat = (float) $priceString;
            $resultPrice = number_format($priceFloat, 2, '.', '');
            if ((int) $resultPrice === 0) {
                $this->addError($model, $attribute, \Yii::t('base', 'Wrong format!'));
            } else {
                $model->$attribute = $resultPrice;
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
