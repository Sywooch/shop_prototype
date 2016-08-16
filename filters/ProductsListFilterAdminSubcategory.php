<?php

namespace app\filters;

use yii\base\ErrorException;
use app\filters\AbstractProductsListFilterAdmin;

/**
 * Заполняет объект корзины данными сесии
 */
class ProductsListFilterAdminSubcategory extends AbstractProductsListFilterAdmin
{
    public function init()
    {
        try {
            parent::init();
            
            $this->_filtersKeyInSession = $this->_filtersKeyInSession . '.admin.subcategory';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
