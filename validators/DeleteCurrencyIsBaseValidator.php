<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\CurrencyIdFinder;

/**
 * Проверяет валидность данных для формы CurrencyForm
 */
class DeleteCurrencyIsBaseValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет является ли удаляемая валюта базовой, 
     * добавляет ошибку, если результат проверки положителен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = new CurrencyIdFinder([
                'id'=>$model->$attribute
            ]);
            $currencyModel = $finder->find();
            
            if ((int) $currencyModel->main === 1) {
                $this->addError($model, $attribute, \Yii::t('base', 'You must first assign a new base currency'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
