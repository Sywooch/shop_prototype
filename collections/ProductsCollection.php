<?php

namespace app\collections;

use yii\base\ErrorException;
use app\collections\{AbstractBaseCollection,
    CollectionInterface};
use app\exceptions\ExceptionsTrait;

/**
 * Реализует интерфейс доступа к данным коллекции сущностей
 */
class ProductsCollection extends AbstractBaseCollection implements CollectionInterface
{
    use ExceptionsTrait;
    
    public function init()
    {
        try {
            if (empty($this->pagination)) {
                throw new ErrorException(ExceptionsTrait::emptyError('pagination'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
