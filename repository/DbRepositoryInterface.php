<?php

namespace app\repository;

use yii\db\Query;
use app\models\QueryCriteriaInterface;

interface DbRepositoryInterface
{
    public function getGroup();
    public function getOne();
    public function setCriteria(QueryCriteriaInterface $criteria);
    public function addCriteria(Query $query);
}
