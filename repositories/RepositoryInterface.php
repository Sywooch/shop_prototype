<?php

namespace app\repositories;

use yii\db\Query;
use app\queries\CriteriaInterface;
use app\models\CollectionInterface;

/**
 * Интерфейс классов-репозиториев
 */
interface RepositoryInterface
{
    public function getGroup($request);
    public function getOne($request);
    public function getCriteria();
    public function setCriteria(CriteriaInterface $criteria);
    public function addCriteria($query);
    public function saveGroup($key);
    public function collectionConfigure($query);
    public function setQuery(Query $query);
    public function getQuery();
    public function setCollection(CollectionInterface $collection);
    public function getCollection();
}
