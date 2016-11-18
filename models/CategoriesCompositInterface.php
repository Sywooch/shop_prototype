<?php

namespace app\models;

use app\models\CategoriesModel;

/**
 * Интерфейс доступа к данным категорий товаров
 */
interface CategoriesCompositInterface
{
    public function add(CategoriesModel $model);
    public function isEmpty();
}
