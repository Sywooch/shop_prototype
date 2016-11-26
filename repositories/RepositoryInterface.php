<?php

namespace app\repositories;

use app\queries\CriteriaInterface;

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
}
