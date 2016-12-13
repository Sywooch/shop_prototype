<?php

namespace app\collections;

use yii\base\Model;

/**
 * Интерфейс коллекции объектов, получаемых из сессии
 */
interface PurchasesCollectionInterface
{
    public function totalQuantity();
    public function totalPrice();
}
