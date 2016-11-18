<?php

namespace app\models;

use app\models\CurrencyModel;

/**
 * Интерфейс доступа к данным currency
 */
interface CurrencyCompositInterface
{
    public function add(CurrencyModel $model);
    public function isEmpty();
}
