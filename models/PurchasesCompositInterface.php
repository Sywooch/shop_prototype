<?php

namespace app\models;

use app\models\PurchasesModel;

/**
 * Интерфейс доступа к коллекции товаров в корзине
 */
interface PurchasesCompositInterface
{
    public function add(PurchasesModel $model);
    public function getQuantity();
    public function getPrice();
    public function isEmpty();
}
