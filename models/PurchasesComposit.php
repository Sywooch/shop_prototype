<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractBaseComposit,
    PurchasesCompositInterface};

/**
 * Реализует интерфейс доступа к данным о покупках в корзине
 */
class PurchasesComposit extends AbstractBaseComposit implements PurchasesCompositInterface
{
    /**
     * Коллекция сущностей
     */
    private $items = [];
    /**
     * @var int общее количество товаров в корзине
     */
    private $quantity = 0;
    /**
     * @var int общая стоимость товаров в корзине
     */
    private $price = 0;
    
    /**
     * Добавляет сущность в коллекцию
     * @param mixed $model
     */
    public function add(PurchasesModel $model)
    {
        try {
            $this->items[] = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает количество товаров в корзине
     * @return int
     */
    public function getQuantity(): int
    {
        try {
            foreach ($this->items as $item) {
                $this->quantity += $item->quantity;
            }
            
            return $this->quantity;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает общую стоимость товаров в корзине
     * @return float
     */
    public function getPrice(): float
    {
        try {
            foreach ($this->items as $item) {
                $this->price += ($item->price * $item->quantity);
            }
            
            return $this->price;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает true, false в зависимости от того, пуст или нет PurchasesComposit::items
     */
    public function isEmpty()
    {
        try {
            return empty($this->items) ? true : false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
