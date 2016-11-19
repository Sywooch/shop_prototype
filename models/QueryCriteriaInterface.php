<?php

namespace app\models;

use yii\db\Query;

/**
 * Интерфейс для применения критериев к SQL запросу
 */
interface QueryCriteriaInterface
{
    public function filter(Query $query);
}
