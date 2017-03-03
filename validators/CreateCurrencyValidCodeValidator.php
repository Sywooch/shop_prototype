<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;

/**
 * Проверяет валидность данных для формы CurrencyForm
 */
class CreateCurrencyValidCodeValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет присутствие кода среди списка допустимых, 
     * добавляет ошибку, если результат проверки отрицателен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            require(\Yii::getAlias('@app/data/codesarray.php'));
            $arrayCodes = json_decode($jsonCodes);
            
            if (in_array($model->$attribute, $arrayCodes) === false) {
                $this->addError($model, $attribute, \Yii::t('base', 'This code is not valid'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
