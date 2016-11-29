<?php

namespace app\collections;

use yii\base\{ErrorException,
    Model};
use app\collections\{AbstractBaseCollection,
    CollectionInterface};

/**
 * Реализует интерфейс доступа к данным коллекции сущностей
 */
class PurchasesCollection extends AbstractBaseCollection implements CollectionInterface
{
    /**
     * Проверяет существование в коллекции сущности с переданным данными
     * @param $object Model
     * @return bool
     */
    public function hasEntity(Model $object): bool
    {
        try {
            if (!empty($this->items)) {
                foreach ($this->items as $item) {
                    if ($item->id_product === $object->id_product) {
                        if (empty(array_diff([$object->id_color, $object->id_size], [$item->id_color, $item->id_size]))) {
                            return true;
                        }
                    }
                }
            }
            
            return false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обновляет данные сущности 
     * @param object $object Model
     * @return bool
     */
    public function update(Model $object)
    {
        try {
            if (!empty($this->items)) {
                foreach ($this->items as $item) {
                    if ($item->id_product === $object->id_product) {
                        $item->quantity += $object->quantity;
                        return true;
                    }
                }
            }
            
            return false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает общее количество товаров в корзине
     * @return int
     */
    public function totalQuantity(): int
    {
        try {
            $result = 0;
            if (!empty($this->items)) {
                foreach ($this->items as $item) {
                    $result += $item->quantity;
                }
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
            $result = 0;
            if (!empty($this->items)) {
                foreach ($this->items as $item) {
                    $result += $item->quantity * $item->price;
                }
            }
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
