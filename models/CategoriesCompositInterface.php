<?php

namespace app\models;

use app\models\CategoriesModel;

/**
 * Интерфейс доступа к коллекции категорий
 */
interface CategoriesCompositInterface
{
    public function add(CategoriesModel $model);
    public function isEmpty();
}
