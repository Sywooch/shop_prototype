<?php

namespace app\collections;

use yii\base\{ErrorException,
    Model};
use app\collections\{AbstractBaseCollection,
    CollectionInterface};

/**
 * Реализует интерфейс доступа к данным коллекции сущностей
 */
class FiltersCollection extends AbstractBaseCollection implements CollectionInterface
{
    public function hasEntity(Model $object)
    {
        try {
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function update(Model $object)
    {
        try {
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
