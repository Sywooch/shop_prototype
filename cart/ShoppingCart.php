<?php

namespace app\cart;

use yii\base\Object;
use app\models\ProductsModel;

/**
 * Предоставляет свойства и методы для работы с корзиной покупок
 */
class ShoppingCart extends Object
{
    /**
     * @var array массив объектов, описывающих выбранные продукты
     * название, артикул, стоимость, кол-во, цвет, размер и т.д.
     */
    private static $_productsArray = array();
    /**
     * @var float общая сумма покупок
     */
    private $_totalCost = 0.00;
    /**
     * @var int общее кол-во товаров в корзине
     */
    private $_totalProducts = 0;
    
    /**
     * Добавляет продукт в массив выбранных к покупке
     */
    public static function addProduct(ProductsModel $object)
    {
        if (!in_array($object, self::$_productsArray)) {
            self::$_productsArray[] = $object;
        }
    }
    
    /**
     * Возвращает значения self::$_productsArray
     */
    public static function getProductsArray()
    {
        return self::$_productsArray;
    }
    
    /**
     * Присваивает восстановленный из сесии массив объектов свойству self::$_productsArray
     */
    public static function setProductsArray(Array $productsArray)
    {
        self::$_productsArray = $productsArray;
    }
    
    /**
     * Заполняет свойства класса краткими данными о товарах в корзине
     */
    public function getShortData()
    {
        if (!empty(self::$_productsArray)) {
            $this->_totalCost = 0.00;
            $this->_totalProducts = 0;
            foreach (self::$_productsArray as $product) {
                $this->_totalCost += $product->price;
                $this->_totalProducts++;
            }
        }
    }
    
    /**
     * Возвращает значение $this->_totalCost
     */
    public function getTotalCost()
    {
        return $this->_totalCost;
    }
    
    /**
     * Возвращает значение $this->_totalProducts
     */
    public function getTotalProducts()
    {
        return $this->_totalProducts;
    }
}
