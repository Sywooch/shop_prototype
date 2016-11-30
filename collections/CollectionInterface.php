<?php

namespace app\collections;

use yii\base\Model;
use app\collections\PaginationInterface;

/**
 * Интерфейс коллекции объектов
 */
interface CollectionInterface
{
    public function add(Model $entity);
    public function isEmpty();
    public function getArray();
    public function setPagination(PaginationInterface $pagination);
    public function getPagination();
    public function map(string $key, string $value);
    public function sort(string $key, $type);
    public function hasEntity(Model $object);
    public function update(Model $object);
}
