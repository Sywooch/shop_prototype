<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;
use app\helpers\MappersHelper;
use app\models\SubcategoryModel;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class SubcategoryNameExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    private static $_message = 'Подкатегория уже существует!';
    
    /**
     * Проверяет, существует ли категория товаров с таким name
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $subcategoryModel = MappersHelper::getSubcategoryByName($model);
            
            if (is_object($subcategoryModel) && $subcategoryModel instanceof SubcategoryModel) {
                $this->addError($model, $attribute, self::$_message);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
