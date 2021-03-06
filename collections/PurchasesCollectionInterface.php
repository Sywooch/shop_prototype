<?php

namespace app\collections;

use yii\base\Model;

/**
 * Интерфейс коллекции покупок
 */
interface PurchasesCollectionInterface
{
    public function addRaw(Model $object);
    public function totalQuantity();
    public function totalPrice();
    public function update(Model $object);
    public function delete(Model $object);
}
