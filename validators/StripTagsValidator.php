<?php

namespace app\validators;

use yii\validators\Validator;
use yii\helpers\HtmlPurifier;
use app\exceptions\ExceptionsTrait;

/**
 * Удаляет HTML и PHP-теги
 */
class StripTagsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * @var string теги, которые не нужно удалять
     */
    public $allowable_tags = '';
    /**
     * @var array массив имен свойств, к значениям которых применяются исключения при очистке от тегов, 
     * в полностью определенном формате app\some\ClassName::propertyName
     */
    public $exceptProperties = [];
    
    /**
     * Инициирует удаление HTML и PHP-тегов из строки, являющейся значением свойства модели
     * @param object $model объект проверяемой модели
     * @param string $attribute имя проверяемого свойства
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $allowable_tags = '';
            
            if (in_array($model::className() . '::' . $attribute, $this->exceptProperties)) {
                $allowable_tags = $this->allowable_tags;
            }
            
            $model->$attribute = $this->strip($model->$attribute, $allowable_tags);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
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
            return $this->strip($value);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Удаляет HTML и PHP-теги из переданной строки
     * @param string $value проверяемая строка
     * @param string $allowable_tags теги, которые не нужно удалять
     * @return string
     */
    private function strip(string $value='', string $allowable_tags=''): string
    {
        try {
            $value = HtmlPurifier::process($value);
            $value = preg_replace('/\s+/', ' ', $value);
            $value = trim($value);
            $value = strip_tags($value, $allowable_tags);
            
            return $value;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
