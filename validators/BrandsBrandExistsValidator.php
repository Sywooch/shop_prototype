<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;
use app\helpers\MappersHelper;
use app\models\BrandsModel;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class BrandsBrandExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    private static $_message = 'Бренд с таким именем уже добавлен!';
    
    /**
     * Проверяет, существует ли бренд с таким именем
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $brandsModel = MappersHelper::getBrandsByBrand($model);
            
            if (is_object($brandsModel) && $brandsModel instanceof BrandsModel) {
                $this->addError($model, $attribute, self::$_message);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
