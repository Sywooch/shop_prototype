<?php

namespace app\collections;

use yii\base\ErrorException;
use app\collections\{AbstractBaseCollection,
    PurchasesCollectionInterface};

/**
 * Коллекция объектов PurchasesModel
 */
class PurchasesCollection extends AbstractBaseCollection implements PurchasesCollectionInterface
{
    /**
     * Возвращает общее количество товаров в корзине
     * @return int
     */
    public function totalQuantity(): int
    {
        try {
            if (empty($this->items)) {
                throw new ErrorException($this->emptyError('items'));
            }
            
            $result = 0;
            
            foreach ($this->items as $item) {
                $result += $item->quantity;
            }
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает общую стоимость товаров в корзине
     * @return float
     */
    public function totalPrice(): float
    {
        try {
            if (empty($this->items)) {
                throw new ErrorException($this->emptyError('items'));
            }
            
            $result = 0;
            
            foreach ($this->items as $item) {
                $result += $item->quantity * $item->price;
            }
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
