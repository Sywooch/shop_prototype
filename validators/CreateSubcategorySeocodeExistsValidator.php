<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\SubcategorySeocodeFinder;

/**
 * Проверяет валидность данных для модели SubcategoryForm
 */
class CreateSubcategorySeocodeExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет уникальность seocode категории, 
     * добавляет ошибку, если результат проверки положителен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = new SubcategorySeocodeFinder([
                'seocode'=>$model->$attribute
            ]);
            $result = $finder->find();
            
            if (!empty($result)) {
                $this->addError($model, $attribute, \Yii::t('base', 'Subcategory with this seocode already exists'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
