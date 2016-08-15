<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;
use app\helpers\MappersHelper;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class SubcategoryForeignProductsExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    private static $_message = 'С подкатегорией связаны товары! Необходимо перенести их перед удалением!';
    
    /**
     * Проверяет, существует ли связь товаров с текущей категорией
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $productsArray = MappersHelper::getProductsByIdSubcategory($model);
            
            if (is_array($productsArray) && !empty($productsArray)) {
                $this->addError($model, $attribute, self::$_message);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
