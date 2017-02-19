<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\ProductDetailFinder;

/**
 * Проверяет валидность данных для модели ProductsModel
 */
class AddProductSeocodeValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет уникальность seocode товара, 
     * корректирует, если результат проверки отрицателен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = new ProductDetailFinder([
                'seocode'=>$model->$attribute
            ]);
            $result = $finder->find();
            
            if (!empty($result)) {
                $model->$attribute = $model->$attribute . sprintf('-%s', mb_strtolower($model->code, 'UTF-8'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
