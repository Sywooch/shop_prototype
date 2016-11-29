<?php

namespace app\collections;

use yii\base\Model;

/**
 * Интерфейс коллекции объектов
 */
interface CollectionInterface
{
    public function add(Model $entity);
    public function isEmpty();
    public function getArray();
    public function hasEntity(Model $object);
    public function update(Model $object);
}
