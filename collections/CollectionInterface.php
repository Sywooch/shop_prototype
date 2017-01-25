<?php

namespace app\collections;

use yii\base\Model;
use app\collections\PaginationInterface;

/**
 * Базовый интерфейс коллекций
 */
interface CollectionInterface
{
    public function setPagination(PaginationInterface $pagination);
    public function getPagination();
    public function add(Model $object);
    public function addArray(array $array);
    public function isEmpty();
    public function multisort(string $key, $type);
    public function map(string $key, string $value);
    public function asArray();
    public function count();
}
