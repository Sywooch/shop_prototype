<?php

namespace app\collections;

use yii\base\Model;

/**
 * Базовый интерфейс коллекций
 */
interface CollectionInterface
{
    public function add(Model $object);
    public function addArray(array $array);
    public function isEmpty();
    public function multisort(string $key, $type);
    public function map(string $key, string $value);
    public function asArray();
}
