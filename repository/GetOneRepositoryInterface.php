<?php

namespace app\repository;

use yii\db\Query;
use app\models\QueryCriteriaInterface;

interface GetOneRepositoryInterface
{
    public function getOne($data);
    public function setCriteria(QueryCriteriaInterface $criteria);
    public function addCriteria(Query $query);
}
