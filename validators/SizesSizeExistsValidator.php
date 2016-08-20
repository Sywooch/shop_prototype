<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;
use app\helpers\MappersHelper;
use app\models\SizesModel;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class SizesSizeExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    private static $_message = 'Такой размер уже добавлен!';
    
    /**
     * Проверяет, существует ли такой размер в БД
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $sizesModel = MappersHelper::getSizesBySize($model);
            
            if (is_object($sizesModel) && $sizesModel instanceof SizesModel) {
                $this->addError($model, $attribute, self::$_message);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
