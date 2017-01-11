<?php

namespace app\collections;

use yii\base\{ErrorException,
    Model};
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
            if ($this->isEmpty() === true) {
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
            if ($this->isEmpty() === true) {
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
    
    
    /**
     * Добавляет объект в коллекцию
     * @param $object Model 
     */
    public function add(Model $object)
    {
        try {
            if ($this->isEmpty() === false) {
                foreach ($this->items as $item) {
                    if ((int) $item->id_product === (int) $object->id_product) {
                        $item->quantity += $object->quantity;
                        return;
                    }
                }
            }
            $this->items[] = $object;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обновляет объект в коллекции
     * @param $object Model 
     */
    public function update(Model $object)
    {
        try {
            if ($this->isEmpty() === true) {
                throw new ErrorException($this->emptyError('items'));
            }
            
            foreach ($this->items as $key=>$item) {
                if ((int) $item->id_product === (int) $object->id_product) {
                    $item->quantity = $object->quantity;
                    $item->id_color = $object->id_color;
                    $item->id_size = $object->id_size;
                    return true;
                }
            }
            return false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
