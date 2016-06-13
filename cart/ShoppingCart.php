<?php

namespace app\cart;

use yii\base\Object;
use app\models\ProductsModel;
use app\traits\ExceptionsTrait;

/**
 * Предоставляет свойства и методы для работы с корзиной покупок
 */
class ShoppingCart extends Object
{
    use ExceptionsTrait;
    
    /**
     * @var array массив объектов, описывающих выбранные продукты
     * название, артикул, стоимость, кол-во, цвет, размер и т.д.
     */
    private static $_productsArray = array();
    /**
     * @var float общая сумма покупок
     */
    private static $_totalCost = 0.00;
    /**
     * @var int общее кол-во товаров в корзине
     */
    private static $_totalProducts = 0;
    
    /**
     * Добавляет продукт в массив выбранных к покупке
     * @param object $object объект модели, представляющий продукт
     * @return boolean
     */
    public static function addProduct(ProductsModel $object)
    {
        try {
            if (!in_array($object, self::$_productsArray)) {
                self::$_productsArray[] = $object;
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return true;
    }
    
    /**
     * Удаляет продукт из массива выбранных к покупке
     * @param object $object объект модели, представляющий продукт
     * @return boolean
     */
    public static function removeProduct(ProductsModel $object)
    {
        try {
            self::$_productsArray = array_udiff(self::$_productsArray, [$object], function($obj_a, $obj_b) {
                return $obj_a->id - $obj_b->id;
            });
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return true;
    }
    
    /**
     * Обновляет данные продукта из массива выбранных к покупке
     * @param object $object объект модели, представляющий продукт
     * @return boolean
     */
    public static function updateProduct(ProductsModel $object)
    {
        try {
            foreach (self::$_productsArray as $element) {
                if ($element->id == $object->id) {
                    foreach ($object as $key=>$value) {
                        $element->$key = $value;
                    }
                }
                break;
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return true;
    }
    
    /**
     * Возвращает значения self::$_productsArray
     * @return array
     */
    public static function getProductsArray()
    {
        return self::$_productsArray;
    }
    
    /**
     * Присваивает восстановленный из сесии массив объектов свойству self::$_productsArray
     * @param array $productsArray массив объектов модели, представляющей продукт
     * @return boolean
     */
    public static function setProductsArray(Array $productsArray)
    {
        try {
            self::$_productsArray = $productsArray;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return true;
    }
    
    /**
     * Очищает массив объектов self::$_productsArray, удаляя все товары из корзины
     * @return boolean
     */
    public static function clearProductsArray()
    {
        try {
            self::$_productsArray = array();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return true;
    }
    
    /**
     * Заполняет свойства класса краткими данными о товарах в корзине
     * @return boolean
     */
    public static function getShortData()
    {
        try {
            if (!empty(self::$_productsArray)) {
                self::$_totalCost = 0.00;
                self::$_totalProducts = 0;
                foreach (self::$_productsArray as $product) {
                    self::$_totalCost += ($product->price * $product->quantity);
                    self::$_totalProducts += $product->quantity;
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return true;
    }
    
    /**
     * Возвращает значение self::$_totalCost
     */
    public static function getTotalCost()
    {
        return self::$_totalCost;
    }
    
    /**
     * Возвращает значение self::$t_totalProducts
     */
    public static function getTotalProducts()
    {
        return self::$_totalProducts;
    }
}
