<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;
use app\helpers\MappersHelper;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class BrandsForeignProductsExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    private static $_message = 'С брендом связаны товары! Необходимо перенести их перед удалением!';
    
    /**
     * Проверяет, существует ли связь товаров с текущим брендом
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $brandsArray = MappersHelper::getProductsBrandsByIdBrands($model);
            
            if (is_array($brandsArray) && !empty($brandsArray)) {
                $this->addError($model, $attribute, self::$_message);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
