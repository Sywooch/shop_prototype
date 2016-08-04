<?php

namespace app\helpers;

use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\models\DeliveriesModel;
use app\models\PaymentsModel;

/**
 * Предоставляет методы для конструирования строк из данных БД
 */
class ObjectsToArrayHelper
{
    use ExceptionsTrait;
    
    private static $_result = array();
    
    /**
     * Конструирует массив данных для формы DeliveriesModel
     * @param array $arrayObjects массив объектов DeliveriesModel
     * @return array
     */
    public static function getDeliveriesArray(Array $arrayObjects)
    {
        try {
            if (empty($arrayObjects) || !is_object($arrayObjects[0]) || !$arrayObjects[0] instanceof DeliveriesModel) {
                throw new ErrorException('Переданы неверные данные!');
            }
            foreach ($arrayObjects as $object) {
                self::$_result[$object->id] = $object->description;
                if ($object->price > 0) {
                    self::$_result[$object->id] .= ' Стоимость доставки: ' . number_format($object->price * \Yii::$app->shopUser->currency->exchange_rate, 2, '.', ' ') . ' ' . \Yii::$app->shopUser->currency->currency;
                }
            }
            return self::$_result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Конструирует массив данных для формы PaymentsModel
     * @param array $arrayObjects массив объектов PaymentsModel
     * @return array
     */
    public static function getPaymentsArray(Array $arrayObjects)
    {
        try {
            if (empty($arrayObjects) || !is_object($arrayObjects[0]) || !$arrayObjects[0] instanceof PaymentsModel) {
                throw new ErrorException('Переданы неверные данные!');
            }
            foreach ($arrayObjects as $object) {
                self::$_result[$object->id] = $object->description;
            }
            return self::$_result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
