<?php

namespace app\repositories;

use yii\db\Query;
use app\queries\CriteriaInterface;
use app\collections\CollectionInterface;

/**
 * Интерфейс классов-репозиториев
 */
interface RepositoryInterface
{
    public function getGroup($request);
    public function getOne($request);
    public function saveGroup($key);
    public function setQuery(Query $query);
    public function setCollection(CollectionInterface $collection);
    public function getCollection();
}
