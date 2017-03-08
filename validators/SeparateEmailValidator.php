<?php

namespace app\validators;

use yii\base\ErrorException;
use yii\validators\EmailValidator;
use app\exceptions\ExceptionsTrait;

/**
 * Проверяет валидность данных для формы CategoriesForm
 */
class SeparateEmailValidator extends EmailValidator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет является ли переданное значение email, 
     * @param string $value проверяемая строка
     * @return mixed
     */
    public function validate($value, &$error=null)
    {
        try {
            $valid = $this->validateValue($value);
            
            if ($valid !== null) {
                throw new ErrorException($this->invalidError($value));
            }
            
            return $value;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
