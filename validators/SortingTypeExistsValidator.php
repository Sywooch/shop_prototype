<?php

namespace app\validators;

use yii\validators\Validator;
use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;
use app\finders\SortingTypesFinder;

/**
 * Проверяет валидность данных полей сортировки
 */
class SortingTypeExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет является ли допустимым переданное поле сортировки
     * фиксирует ошибку, если результат проверки отрицателен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
            $sortingTypesArray = $finder->find();
            if (empty($sortingTypesArray)) {
                throw new ErrorException($this->emptyError('sortingTypesArray'));
            }
            
            if (in_array($model->$attribute, $sortingTypesArray) === false) {
                throw new ErrorException($this->invalidRange('sortingType'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
