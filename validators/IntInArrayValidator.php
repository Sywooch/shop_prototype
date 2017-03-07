<?php

namespace app\validators;

use yii\validators\Validator;
use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;

/**
 * Проверяет валидность данных полей сортировки
 */
class IntInArrayValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет содержит ли атрибут допустимые значения
     * выбрасывает исключенние, если результат проверки отрицателен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $dataArray = $model->$attribute;
            if (is_array($dataArray) === false) {
                throw new ErrorException($this->emptyError('dataArray'));
            }
            
            foreach ($dataArray as $item) {
                if (is_numeric($item) === false) {
                    throw new ErrorException($this->invalidRange($attribute));
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
