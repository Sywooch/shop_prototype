<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\SubcategoryIdCategoryFinder;

/**
 * Проверяет валидность данных для формы CategoriesForm
 */
class DeleteCategorySubcategoryExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет существуют ли подкатегории для удаляемой категории, 
     * добавляет ошибку, если результат проверки положителен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = new SubcategoryIdCategoryFinder([
                'id_category'=>$model->$attribute
            ]);
            $result = $finder->find();
            
            if (!empty($result)) {
                $this->addError($model, $attribute, \Yii::t('base', 'You must first delete or transfer subcategories'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
