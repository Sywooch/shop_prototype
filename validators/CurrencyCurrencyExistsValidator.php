<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;
use app\helpers\MappersHelper;
use app\models\CurrencyModel;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class CurrencyCurrencyExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    private static $_message = 'Валюта с таким именем уже добавлена!';
    
    /**
     * Проверяет, существует ли валюта с таким именем
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $currencyModel = MappersHelper::getCurrencyByCurrency($model);
            
            if (is_object($currencyModel) && $currencyModel instanceof CurrencyModel) {
                $this->addError($model, $attribute, self::$_message);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
