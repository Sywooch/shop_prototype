<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;

/**
 * Проверяет уникальность данный свойста ProductsModel::code
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
            if (ProductsModel::find()->where(['[[products.code]]'=>$model->$attribute])->exists()) {
                $this->addError($model, $attribute, \Yii::t('base', 'Product with this code already exists!'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
