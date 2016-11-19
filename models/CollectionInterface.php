<?php

namespace app\models;

/**
 * Интерфейс коллекции объектов
 */
interface CollectionInterface
{
    public function add($object);
    public function isEmpty();
}
