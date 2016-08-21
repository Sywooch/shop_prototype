<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;
use app\helpers\MappersHelper;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class SizesForeignProductsExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    private static $_message = 'С размером связаны товары! Необходимо перенести их перед удалением!';
    
    /**
     * Проверяет, существует ли связь товаров с текущим удаляемым размером
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $productsSizesArray = MappersHelper::getProductsSizesByIdSizes($model);
            
            if (is_array($productsSizesArray) && !empty($productsSizesArray)) {
                $this->addError($model, $attribute, self::$_message);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
