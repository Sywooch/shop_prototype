<?php

namespace app\models;

use yii\base\{ErrorException,
    Model};
use app\models\{AbstractBaseCollection,
    CollectionInterface};

/**
 * Реализует интерфейс доступа к данным коллекции сущностей
 */
class ProductsCollection extends AbstractBaseCollection implements CollectionInterface
{
    /**
     * Проверяет существование в коллекции сущности с переданным данными
     * @param object $object Model
     * @return bool
     */
    public function hasEntity(Model $object)
    {
        try {
            
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
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
