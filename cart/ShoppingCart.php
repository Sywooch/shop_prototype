<?php

namespace app\cart;

use yii\base\Object;
use app\traits\ExceptionsTrait;
use app\models\ProductsModel;
use app\models\UsersModel;

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
    private $_productsArray = array();
    /**
     * @var float общая сумма покупок
     */
    private $_totalCost = 0.00;
    /**
     * @var int общее кол-во товаров в корзине
     */
    private $_totalProducts = 0;
    /**
     * @var object объект пользователя, связанного с заказами в корзине
     */
    public $user;
    
    /**
     * Добавляет продукт в массив выбранных к покупке
     * @param object $object объект модели, представляющий продукт
     * @return boolean
     */
    public function addProduct(ProductsModel $object)
    {
        try {
            foreach ($this->_productsArray as $objectInArray) {
                if ($objectInArray->id == $object->id) {
                    $objectInArray->quantity++;
                    return false;
                }
            }
            $this->_productsArray[] = $object;
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
    public function removeProduct(ProductsModel $object)
    {
        try {
            $this->_productsArray = array_udiff($this->_productsArray, [$object], function($obj_a, $obj_b) {
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
    public function updateProduct(ProductsModel $object)
    {
        try {
            foreach ($this->_productsArray as $objectInArray) {
                if ($objectInArray->id == $object->id) {
                    foreach ($object as $key=>$value) {
                        if (!empty($value)) {
                            $objectInArray->$key = $value;
                        }
                    }
                    break;
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return true;
    }
    
    /**
     * Возвращает значения $this->_productsArray
     * @return array
     */
    public function getProductsArray()
    {
        return $this->_productsArray;
    }
    
    /**
     * Присваивает восстановленный из сесии массив объектов свойству $this->_productsArray
     * @param array $productsArray массив объектов модели, представляющей продукт
     * @return boolean
     */
    public function setProductsArray(Array $productsArray)
    {
        try {
            $this->_productsArray = $productsArray;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return true;
    }
    
    /**
     * Очищает массив объектов $this->_productsArray, удаляя все товары из корзины
     * @return boolean
     */
    public function clearProductsArray()
    {
        try {
            $this->_productsArray = array();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return true;
    }
    
    /**
     * Заполняет свойства класса краткими данными о товарах в корзине
     * @return boolean
     */
    public function getShortData()
    {
        try {
            if (!empty($this->_productsArray)) {
                $this->_totalCost = 0.00;
                $this->_totalProducts = 0;
                foreach ($this->_productsArray as $objectInArray) {
                    $this->_totalCost += ($objectInArray->price * $objectInArray->quantity);
                    $this->_totalProducts += $objectInArray->quantity;
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return true;
    }
    
    /**
     * Возвращает значение $this->_totalCost
     * @return int
     */
    public function getTotalCost()
    {
        return $this->_totalCost;
    }
    
    /**
     * Возвращает значение $this->t_totalProducts
     * @return int
     */
    public function getTotalProducts()
    {
        return $this->_totalProducts;
    }
}
