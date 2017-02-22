<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\SubcategoryNameFinder;

/**
 * Проверяет валидность данных для модели SubcategoryForm
 */
class CreateSubcategoryNameExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет уникальность имени категории, 
     * добавляет ошибку, если результат проверки положителен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = new SubcategoryNameFinder([
                'name'=>$model->$attribute
            ]);
            $result = $finder->find();
            
            if (!empty($result)) {
                $this->addError($model, $attribute, \Yii::t('base', 'Subcategory with this name already exists'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
