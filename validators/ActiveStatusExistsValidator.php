<?php

namespace app\validators;

use yii\validators\Validator;
use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;
use app\finders\ActiveStatusesFinder;

/**
 * Проверяет валидность данных полей сортировки
 */
class ActiveStatusExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет является ли допустимым переданное поле сортировки
     * выбрасывает исключенние, если результат проверки отрицателен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = \Yii::$app->registry->get(ActiveStatusesFinder::class);
            $activeStatusesArray = $finder->find();
            if (empty($activeStatusesArray)) {
                throw new ErrorException($this->emptyError('activeStatusesArray'));
            }
            
            if (array_key_exists($model->$attribute, $activeStatusesArray) === false) {
                throw new ErrorException($this->invalidRange($attribute));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
