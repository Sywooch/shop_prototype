<?php

namespace app\models;

use yii\base\Model;

/**
 * Интерфейс коллекции объектов
 */
interface CollectionInterface
{
    public function add(Model $object);
    public function addOne(Model $object);
    public function isEmpty();
    public function getArray();
    public function hasEntity(Model $object);
    public function update(Model $object);
}
