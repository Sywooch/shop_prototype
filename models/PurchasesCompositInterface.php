<?php

namespace app\models;

use app\models\PurchasesModel;

/**
 * Интерфейс доступа к данным о покупках в корзине
 */
interface PurchasesCompositInterface
{
    public function add(PurchasesModel $model);
    public function getQuantity();
    public function getPrice();
    public function isEmpty();
}
