<?php

namespace app\models;

/**
 * Интерфейс коллекции объектов
 */
interface CollectionInterface
{
    public function add($object);
    public function isEmpty();
    public function getByKey(string $key, $value);
    public function getArray();
}
