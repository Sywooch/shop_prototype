<?php

namespace app\filters;

use yii\base\ErrorException;
use app\filters\ProductsListFilterAdmin;

/**
 * Заполняет объект корзины данными сесии
 */
class ProductsListFilterCSV extends ProductsListFilterAdmin
{
    public function init()
    {
        try {
            parent::init();
            
            $this->_filtersKeyInSession = $this->_filtersKeyInSession . '.csv';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
