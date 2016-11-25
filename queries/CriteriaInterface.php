<?php

namespace app\queries;

use app\filters\FilterInterface;

/**
 * Интерфейс для применения критериев к запросу
 */
interface CriteriaInterface
{
    public function apply($query);
    public function setFilter(FilterInterface $filter);
}
