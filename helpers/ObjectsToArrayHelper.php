<?php

namespace app\helpers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\traits\ExceptionsTrait;
use app\models\{DeliveriesModel,
    PaymentsModel, 
    CategoriesModel};

/**
 * Предоставляет методы для конструирования строк из данных БД
 */
class ObjectsToArrayHelper
{
    use ExceptionsTrait;
    
    private static $_result = array();
    public static $allowCategories = ['Мужская обувь', 'Мужская одежда'];
    
    /**
     * Конструирует массив данных для формы выбора способа доставки
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
     * Конструирует массив данных для формы выбора способа оплаты
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
    
    /**
     * Конструирует массив CategoriesModel для формы добавления продукта
     * @param array $arrayObjects массив объектов CategoriesModel
     * @return array
     */
     public static function getCategoriesToAddProductArray(Array $arrayObjects)
     {
        try {
            if (empty($arrayObjects) || !is_object($arrayObjects[0]) || !$arrayObjects[0] instanceof CategoriesModel) {
                throw new ErrorException('Переданы неверные данные!');
            }
            foreach ($arrayObjects as $object) {
                if (in_array($object->name, self::$allowCategories)) {
                    self::$_result[$object->id] = $object->name;
                }
            }
            return self::$_result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
     }
}
