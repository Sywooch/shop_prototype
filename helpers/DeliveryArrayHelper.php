<?php

namespace app\helpers;

use app\traits\ExceptionsTrait;
use yii\helpers\ArrayHelper;

/**
 * Предоставляет методы для транслитерации
 */
class DeliveryArrayHelper
{
    use ExceptionsTrait;
    
    private static $_result = array();
    
    /**
     * Конструирует массив данных для формы DeliveriesModel
     * @param array $arrayObjects массив объектов DeliveriesModel
     * @return array
     */
    public static function getDeliveriesArray($arrayObjects)
    {
        try {
            foreach ($arrayObjects as $object) {
                self::$_result[$object->id] = $object->name . '. ' . $object->description;
                if ($object->price > 0) {
                    self::$_result[$object->id] .= ' Стоимость доставки: ' . $object->price;
                }
            }
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
        return self::$_result;
    }
    
    /**
     * Конструирует массив данных для формы PaymentsModel
     * @param array $arrayObjects массив объектов PaymentsModel
     * @return array
     */
    public static function getPaymentsArray($arrayObjects)
    {
        try {
            foreach ($arrayObjects as $object) {
                self::$_result[$object->id] = $object->name . '. ' . $object->description;
            }
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
        return self::$_result;
    }
}
