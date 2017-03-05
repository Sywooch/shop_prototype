<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\OrdersIdPaymentFinder;

/**
 * Проверяет валидность данных для формы PaymentsForm
 */
class DeletePaymentOrdersExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет существование заказов, связанных с проверяемым типом оплаты, 
     * добавляет ошибку, если результат проверки положителен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = new OrdersIdPaymentFinder([
                'id_payment'=>$model->$attribute
            ]);
            $result = $finder->find();
            
            if (!empty($result)) {
                $this->addError($model, $attribute, \Yii::t('base', 'With this type of payment associated orders. First, you must delete or move them'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
