<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\DeliveryNameFinder;

/**
 * Проверяет валидность данных для формы ColorsForm
 */
class CreateDeliveryNameExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет уникальность названия типа доставки в СУБД, 
     * добавляет ошибку, если результат проверки положителен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = new DeliveryNameFinder([
                'name'=>$model->$attribute
            ]);
            $result = $finder->find();
            
            if (!empty($result)) {
                $this->addError($model, $attribute, \Yii::t('base', 'Delivery with this name already exists'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
