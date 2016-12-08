<?php

namespace app\collections;

use yii\base\Model;
use yii\db\Query;
use app\collections\PaginationInterface;

/**
 * Интерфейс коллекции объектов
 */
interface CollectionInterface
{
    public function setQuery(Query $query);
    public function getQuery();
    public function add(Model $object);
    public function addArray(array $array);
    public function isEmpty();
    public function isArrays();
    public function isObjects();
    public function getModels();
    public function getArrays();
    public function getModel();
    public function getArray();
    public function setPagination(PaginationInterface $pagination);
    public function getPagination();
    public function map(string $key, string $value);
    public function column(string $key);
    public function sort(string $key, $type);
    public function hasEntity(Model $object);
    public function update(Model $object);
}
