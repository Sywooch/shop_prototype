<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\ProductsIdSizeFinder;

/**
 * Проверяет валидность данных для формы SizesForm
 */
class DeleteSizeProductsExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет существуют ли товары, связанные с удаляемым размером, 
     * добавляет ошибку, если результат проверки положителен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = new ProductsIdSizeFinder([
                'id_size'=>$model->$attribute
            ]);
            $result = $finder->find();
            
            if (!empty($result)) {
                $this->addError($model, $attribute, \Yii::t('base', 'You must first delete or transfer products'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
