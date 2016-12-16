<?php

namespace app\collections;

use yii\db\Query;

/**
 * Интерфейс пагинатора
 */
interface PaginationInterface
{
    public function setPageSize(int $size);
    public function setPage(int $number);
    public function setTotalCount(Query $query);
    public function getTotalCount();
    public function getPageCount();
    public function getOffset();
    public function getLimit();
    public function getPage();
}
