<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;
use app\helpers\MappersHelper;
use app\models\CategoriesModel;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class CategorySeocodeExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    private static $_message = 'Этот код уже используется!';
    
    /**
     * Проверяет, существует ли категория товаров с таким name
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $categoriesModel = MappersHelper::getCategoriesBySeocode($model);
            
            if (is_object($categoriesModel) && $categoriesModel instanceof CategoriesModel) {
                $this->addError($model, $attribute, self::$_message);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
