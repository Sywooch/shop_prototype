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
     * в полностью определенном формате ClassName::propertyName
     */
    public $exceptProperties = [];
    
    /**
     * Инициирует удаление HTML и PHP-тегов из строки
     * @param object $model объект проверяемой модели
     * @param string $attribute имя проверяемого свойства
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $rawAttribute = $model->$attribute;
            
            if (!empty($rawAttribute)) {
                if (in_array($model::className() . '::' . $attribute, $this->exceptProperties)) {
                    $allowable_tags = $this->allowable_tags;
                }
                
                if (is_array($rawAttribute)) {
                    $resultArray = [];
                    foreach ($rawAttribute as $item) {
                        $resultArray[] = $this->strip($item, $allowable_tags ?? '');
                    }
                    $model->$attribute = $resultArray;
                } else {
                    $model->$attribute = $this->strip($rawAttribute, $allowable_tags ?? '');
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Инициирует удаление HTML и PHP-тегов из любой переданной строки
     * @param string $value проверяемая строка
     * @return mixed
     */
    public function validate($value, &$error=null)
    {
        try {
            if (!empty($value) && is_string($value)) {
                $value = $this->strip($value);
            }
            
            return $value;
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
    private function strip(string $value, string $allowable_tags=''): string
    {
        try {
            $value = HtmlPurifier::process($value, [
                'HTML.Allowed'=>$allowable_tags,
                'Core.RemoveProcessingInstructions'=>true
            ]);
            $value = preg_replace('/\s+/u', ' ', $value);
            $value = trim($value);
            
            return $value;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
