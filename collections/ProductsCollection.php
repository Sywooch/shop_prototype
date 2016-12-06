<?php

namespace app\collections;

use yii\base\ErrorException;
use app\collections\AbstractBaseCollection;

/**
 * Реализует интерфейс доступа к коллекции товаров
 */
class ProductsCollection extends AbstractBaseCollection
{
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->pagination)) {
                throw new ErrorException($this->emptyError('pagination'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
