<?php

namespace app\models;

use yii\base\{ErrorException,
    Model};
use app\models\{AbstractBaseCollection,
    CollectionInterface};

/**
 * Реализует интерфейс доступа к данным коллекции сущностей
 */
class PurchasesCollection extends AbstractBaseCollection implements CollectionInterface
{
    /**
     * Проверяет существование в коллекции сущности с переданным данными
     * @param object $object Model
     * @return bool
     */
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
    
    /**
     * Обновляет данные сущности 
     * @param object $object Model
     * @return bool
     */
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
}
