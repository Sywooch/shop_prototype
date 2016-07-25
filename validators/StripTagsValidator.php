<?php

namespace app\validators;

use yii\validators\Validator;
use yii\helpers\HtmlPurifier;
use app\traits\ExceptionsTrait;

/**
 * Удаляет HTML и PHP-теги
 */
class StripTagsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * @var string теги, которые не нужно удалять
     */
    public $allowable_tags = '<p></p><ul></ul><li></li><strong></strong>';
    /**
     * @var array массив свойств, к значениям которых применяются исключения при очистке от тегов
     */
    public $exceptProreties = ['app\models\ProductsModel::description'];
    
    /**
     * Инициирует удаление HTML и PHP-тегов из строки, являющейся значением свойства модели
     * @param object $model объект проверяемой модели
     * @param string $attribute имя проверяемого свойства
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $allowable_tags = '';
            if (in_array($model::className() . '::' . $attribute, $this->exceptProreties)) {
                $allowable_tags = $this->allowable_tags;
            }
            if ($result = $this->strip($model->$attribute, $allowable_tags)) {
                $model->$attribute = $result;
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инициирует удаление HTML и PHP-тегов из любой переданной строки
     * @param string $value проверяемая строка
     * @return string
     */
    public function validate($value, &$error=null)
    {
        try {
            if ($result = $this->strip($value)) {
                return $result;
            }
            return $value;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет HTML и PHP-теги из переданной строки
     * @param string $value проверяемая строка
     * @param string $allowable_tags теги, которые не нужно удалять
     * @return string
     */
    private function strip($value, $allowable_tags='')
    {
        try {
            if (is_string($value)) {
                $value = HtmlPurifier::process($value);
                $value = preg_replace('/\s{2,}?/', ' ', $value);
                $value = trim($value);
                $value = strip_tags($value, $allowable_tags);
                return $value;
            }
            return false;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
