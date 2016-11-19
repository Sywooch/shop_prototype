<?php

namespace app\models;

use app\models\ProductsModel;

/**
 * Интерфейс доступа к коллекции товаров
 */
interface ProductsCompositInterface
{
    public function add(ProductsModel $model);
    public function isEmpty();
}
