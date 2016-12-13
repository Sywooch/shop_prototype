<?php

namespace app\collections;

/**
 * Интерфейс коллекции покупок
 */
interface PurchasesCollectionInterface
{
    public function totalQuantity();
    public function totalPrice();
}
