<?php

namespace app\filters;

use yii\base\ErrorException;
use app\filters\AbstractProductsListFilterAdmin;

/**
 * Заполняет объект корзины данными сесии
 */
class CommentsFilterAdmin extends AbstractProductsListFilterAdmin
{
    public function init()
    {
        try {
            parent::init();
            
            $this->_filtersKeyInSession = $this->_filtersKeyInSession . '.admin.comments';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
