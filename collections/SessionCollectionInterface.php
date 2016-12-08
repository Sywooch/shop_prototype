<?php

namespace app\collections;

use yii\base\Model;

/**
 * Интерфейс коллекции объектов, получаемых из сессии
 */
interface SessionCollectionInterface
{
    public function addArray(array $array);
    public function isEmpty();
    public function isArrays();
    public function isObjects();
    public function getModels(string $class);
    public function getModel(string $class);
    public function getArray();
    public function map(string $key, string $value);
    public function sort(string $key, $type);
    public function hasEntity(Model $object);
    public function update(Model $object);
}
