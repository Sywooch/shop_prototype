<?php

namespace app\collections;

use yii\base\{ErrorException,
    Model};
use app\collections\BaseSessionCollection;
use app\exceptions\ExceptionsTrait;
use app\models\PurchasesModel;

/**
 * Реализует интерфейс доступа к данным коллекции сущностей
 */
class PurchasesSessionCollection extends BaseSessionCollection
{
    /**
     * Получает объекты из сессии и добавляет их в коллекцию
     * @return $this
     */
    public function getModels()
    {
        try {
            if ($this->isEmpty() === false) {
                if ($this->isArrays() === false) {
                    throw new ErrorException($this->invalidError('items'));
                }
                
                $objectsArray = [];
                
                foreach ($this->items as $item) {
                    $objectsArray[] = new PurchasesModel($item);
                }
                
                $this->items = $objectsArray;
            }
            
            return $this;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет существование в коллекции элемента с переданным данными
     * @param $object Model
     * @return bool
     */
    public function hasEntity(Model $object): bool
    {
        try {
            if ($this->isEmpty()) {
                throw new ErrorException($this->emptyError('items'));
            }
            
            foreach ($this->items as $item) {
                if (is_array($item)) {
                    if ($item['id_product'] === $object->id_product) {
                        if (empty(array_diff([$object->id_color, $object->id_size], [$item['id_color'], $item['id_size']]))) {
                            return true;
                        }
                    }
                } else {
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
     * Обновляет данные 
     * @param object $object Model
     */
    public function update(Model $object)
    {
        try {
            if ($this->isEmpty()) {
                throw new ErrorException($this->emptyError('items'));
            }
            
            foreach ($this->items as &$item) {
                if (is_array($item)) {
                    if ($item['id_product'] === $object->id_product) {
                        $item['quantity'] += $object->quantity;
                    }
                } else {
                    if ($item->id_product === $object->id_product) {
                        $item->quantity += $object->quantity;
                    }
                }
            }
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
            if ($this->isEmpty()) {
                throw new ErrorException($this->emptyError('items'));
            }
            
            $result = 0;
            
            foreach ($this->items as $item) {
                if (is_array($item)) {
                    $result += $item['quantity'];
                } else {
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
            if ($this->isEmpty()) {
                throw new ErrorException($this->emptyError('items'));
            }
            
            $result = 0;
            
            foreach ($this->items as $item) {
                if (is_array($item)) {
                    $result += $item['quantity'] * $item['price'];
                } else {
                    $result += $item->quantity * $item->price;
                }
            }
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
