<?php

namespace app\models;

use app\models\CurrencyModel;

/**
 * Интерфейс доступа к коллекции валют
 */
interface CurrencyCompositInterface
{
    public function add(CurrencyModel $model);
    public function isEmpty();
}
