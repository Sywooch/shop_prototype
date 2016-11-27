<?php

namespace app\models;

use yii\base\{ErrorException,
    Model};
use yii\helpers\ArrayHelper;
use app\models\{AbstractBaseCollection,
    CollectionInterface};

/**
 * Реализует интерфейс доступа к данным коллекции сущностей
 */
class Collection extends AbstractBaseCollection implements CollectionInterface
{
    public function hasEntity(Model $object)
    {
        try {
            if (!empty($this->items)) {
                foreach ($this->items as $item) {
                    if ($item->id_product === $object->id_product) {
                        if (empty(array_diff([$object->id_color, $object->id_size], [$item->id_color, $item->id_size]))) {
                            return true;
                        }
                    }
                }
            }
            
            return false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function update(Model $object)
    {
        try {
            if (!empty($this->items)) {
                foreach ($this->items as $item) {
                    if ($item->id_product === $object->id_product) {
                        $item->quantity += $object->quantity;
                        return true;
                    }
                }
            }
            
            return false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function map($key, $value)
    {
        try {
            return ArrayHelper::map($this->items, $key, $value);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function sort($key, $type=SORT_ASC)
    {
        try {
            ArrayHelper::multisort($this->items, $key, $type);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
