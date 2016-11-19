<?php

namespace app\models;

/**
 * Интерфейс коллекции объектов
 */
interface CompositInterface
{
    public function add($object);
    public function isEmpty();
}
