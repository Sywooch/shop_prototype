<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;
use app\helpers\MappersHelper;
use app\models\ColorsModel;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class ColorsColorExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    private static $_message = 'Такой цвет уже добавлен!';
    
    /**
     * Проверяет, существует ли такой цвет в БД
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $colorsModel = MappersHelper::getColorsByColor($model);
            
            if (is_object($colorsModel) && $colorsModel instanceof ColorsModel) {
                $this->addError($model, $attribute, self::$_message);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
