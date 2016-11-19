<?php

namespace app\repository;

use app\models\QueryCriteriaInterface;

/**
 * Интерфейс классов-репозиториев
 */
interface RepositoryInterface
{
    public function getGroup($data);
    public function getOne($data);
    public function setCriteria(QueryCriteriaInterface $criteria);
    public function addCriteria($query);
}
