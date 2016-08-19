<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;
use app\helpers\MappersHelper;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class ColorsForeignProductsExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    private static $_message = 'С цветом связаны товары! Необходимо перенести их перед удалением!';
    
    /**
     * Проверяет, существует ли связь товаров с текущим удаляемым цветом
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $productsColorsArray = MappersHelper::getProductsColorsByIdColors($model);
            
            if (is_array($productsColorsArray) && !empty($productsColorsArray)) {
                $this->addError($model, $attribute, self::$_message);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
