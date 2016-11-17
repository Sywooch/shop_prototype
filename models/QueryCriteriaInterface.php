<?php

namespace app\models;

use yii\db\Query;

interface QueryCriteriaInterface
{
    public function filter(Query $query);
}
