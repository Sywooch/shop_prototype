<?php

namespace app\models;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;
use app\helpers\{HashHelper,
    SessionHelper};

/**
 * Представляет данные корзины заказов
 */
class CartModel extends Model
{
    use ExceptionsTrait;
    
    /**
     * @var array массив данных, представляющих товары в корзине
     */
    private $purchasesArray = [];
    /**
     * @var int общее количество товаров в корзине
     */
    private $goods = 0;
    /**
     * @var int общая стоимость товаров в корзине
     */
    private $totalCost = 0;
    
    /**
     * Добавляет покупку в корзину
     */
    public function add(array $purchase)
    {
        try {
            $id = $purchase['id_product'];
            
            if (array_key_exists($id, $this->purchasesArray)) {
                $this->purchasesArray[$id]['quantity'] += $purchase['quantity'];
            } else {
                $this->purchasesArray[$id] = $purchase;
            }
            
            $this->writeSession();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    private function writeSession()
    {
        try {
            $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            SessionHelper::write($cartKey, $this->purchasesArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет массив товаров в свойстве CartModel::purchasesArray
     */
    public function setPurchases(array $purchases)
    {
        try {
            if (empty($this->purchasesArray)) {
                $this->purchasesArray = $purchases;
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает общее количество товаров в корзине
     * @return int
     */
    public function getGoods(): int
    {
        try {
            if (!empty($this->purchasesArray)) {
                foreach ($this->purchasesArray as $purchase) {
                    $this->goods += $purchase['quantity'];
                }
            }
            
            return $this->goods;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает общую стоимость товаров в корзине
     * @return float
     */
    public function getTotalCost(): float
    {
        try {
            if (!empty($this->purchasesArray)) {
                foreach ($this->purchasesArray as $purchase) {
                    $this->totalCost += ($purchase['price'] * $purchase['quantity']);
                }
            }
            
            return $this->totalCost;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
