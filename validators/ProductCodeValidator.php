<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;

/**
 * Проверяет уникальность данных ProductsModel::code
 */
class ProductCodeValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * @param object $model объект проверяемой модели
     * @param string $attribute имя проверяемого свойства
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $productsQuery = ProductsModel::find();
            $productsQuery->where(['[[products.code]]'=>$model->$attribute]);
            $result = $productsQuery->exists();
            
            if ($result) {
                $this->addError($model, $attribute, \Yii::t('base', 'Product with this code already exists!'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
