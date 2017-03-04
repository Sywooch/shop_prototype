<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\OrdersIdDeliveryFinder;

/**
 * Проверяет валидность данных для формы DeliveriesForm
 */
class DeleteDeliveryOrdersExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет существование заказов, связанных с проверяемым типом доставки, 
     * добавляет ошибку, если результат проверки положителен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = new OrdersIdDeliveryFinder([
                'id_delivery'=>$model->$attribute
            ]);
            $result = $finder->find();
            
            if (!empty($result)) {
                $this->addError($model, $attribute, \Yii::t('base', 'With this type of delivery associated orders. You must first delete or transfer them'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
