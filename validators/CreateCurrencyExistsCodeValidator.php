<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\CurrencyCodeFinder;

/**
 * Проверяет валидность данных для формы CurrencyForm
 */
class CreateCurrencyExistsCodeValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет существование валюты с переданным кодом, 
     * добавляет ошибку, если результат проверки положителен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = new CurrencyCodeFinder([
                'code'=>$model->$attribute
            ]);
            $result = $finder->find();
            
            if (!empty($result)) {
                $this->addError($model, $attribute, \Yii::t('base', 'Currency with this code already exist'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
