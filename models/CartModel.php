<?php

namespace app\models;

use yii\base\{ErrorException,
    Model};
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\helpers\{HashHelper,
    SessionHelper};
use app\models\{ProductsModel,
    PurchasesModel};

/**
 * Представляет данные корзины заказов
 */
class CartModel extends Model
{
    use ExceptionsTrait;
    
    /**
     * @var array массив объектов PurchasesModel, представляющих товары в корзине
     */
    private $_purchasesArray = [];
    /**
     * @var int общее количество товаров в корзине
     */
    private $_goods = 0;
    /**
     * @var int общая стоимость товаров в корзине
     */
    private $_totalCost = 0;
    
    /**
     * Добавляет покупку в корзину
     */
    public function add(PurchasesModel $purchasesModel)
    {
        try {
            if ($purchasesModel->validate()) {
                return $this->write($purchasesModel);
            }
            
            return $purchasesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет покупку в массиве CartModel::_purchasesArray
     * Пишет в сессию данные о товарах в корзине
     * @param object $purchasesModel PurchasesModel
     * @return bool
     */
    private function write(PurchasesModel $purchasesModel): bool
    {
        try {
            $hash = HashHelper::createHash($purchasesModel->toHash());
            
            if (array_key_exists($hash, $this->_purchasesArray)) {
                $this->_purchasesArray[$hash]->quantity += $purchasesModel->quantity;
            } else {
                $this->_purchasesArray[$hash] = $purchasesModel;
            }
            
            $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            SessionHelper::write($cartKey, $this->_purchasesArray);
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет массив товаров в свойстве CartModel::_purchasesArray
     */
    public function setPurchases(array $purchases)
    {
        try {
            if (empty($this->_purchasesArray)) {
                $this->_purchasesArray = $purchases;
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
            if (!empty($this->_purchasesArray)) {
                foreach ($this->_purchasesArray as $purchase) {
                    $this->_goods += $purchase->quantity;
                }
            }
            
            return $this->_goods;
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
            if (!empty($this->_purchasesArray)) {
                $productsArray = ProductsModel::findAll(ArrayHelper::getColumn($this->_purchasesArray, 'id_product', false));
                $productsArray = ArrayHelper::map($productsArray, 'id', 'price');
                
                foreach ($this->_purchasesArray as $purchase) {
                    $this->_totalCost += ($productsArray[$purchase->id_product] * $purchase->quantity);
                }
            }
            
            return $this->_totalCost;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
