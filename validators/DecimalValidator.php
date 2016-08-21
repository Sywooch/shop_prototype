<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class DecimalValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * @var int число знаков после запятой
     */
    public $decimals = 1;
    /**
     * @var string разделитель дробной части
     */
    public $dec_point = '.';
    /**
     * @var string разделитель тысяч
     */
    public $thousands_sep = '';
    
    /**
     * Форматирует переданное значение в число с плавающей точкой
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            if (is_numeric($model->$attribute)) {
                $model->$attribute = number_format($model->$attribute, $this->decimals, $this->dec_point, $this->thousands_sep);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
