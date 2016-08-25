<?php

namespace app\filters;

use yii\base\ErrorException;
use app\filters\AbstractFilterAdmin;

/**
 * Заполняет объект корзины данными сесии
 */
class SubcategoryFilterAdmin extends AbstractFilterAdmin
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
