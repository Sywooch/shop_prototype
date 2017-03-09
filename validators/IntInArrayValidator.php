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
            $rawDataArray = $model->$attribute;
            if (is_array($rawDataArray) === false) {
                throw new ErrorException($this->invalidRange($attribute));
            }
            
            if (!empty($rawDataArray)) {
                $postFilterArray = [];
                
                foreach ($rawDataArray as $item) {
                    $item = filter_var($item, FILTER_VALIDATE_INT);
                    if ($item === false) {
                        throw new ErrorException($this->invalidRange($attribute));
                    }
                    $postFilterArray[] = $item;
                }
                
                $model->$attribute = $postFilterArray;
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
