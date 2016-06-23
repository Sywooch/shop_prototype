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
     * Конструирует массив данных для форм
     * @return array
     */
    public static function getArray($arrayObjects)
    {
        try {
            foreach ($arrayObjects as $object) {
                if ($object->price > 0) {
                    $price = 'Стоимость: ' . $object->price;
                } else {
                    $price = 'Стоимость уточнит менеджер.';
                }
                self::$_result[$object->id] = $object->name . '. ' . $object->description . '. ' . $price;
            }
        } catch (\Exception $e) {
            self::throwStaticException($e, __METHOD__);
        }
        return self::$_result;
    }
}
