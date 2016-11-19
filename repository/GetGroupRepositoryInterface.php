<?php

namespace app\repository;

use yii\db\Query;
use app\models\QueryCriteriaInterface;

interface GetGroupRepositoryInterface
{
    public function getGroup($data);
    public function setCriteria(QueryCriteriaInterface $criteria);
    public function addCriteria(Query $query);
}
