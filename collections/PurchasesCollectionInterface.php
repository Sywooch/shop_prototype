<?php

namespace app\collections;

use yii\base\Model;

/**
 * Интерфейс коллекции покупок
 */
interface PurchasesCollectionInterface
{
    public function totalQuantity();
    public function totalPrice();
    public function update(Model $object);
}
