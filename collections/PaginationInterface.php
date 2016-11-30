<?php

namespace app\collections;

use yii\db\Query;

interface PaginationInterface
{
    public function setPageSize(int $size);
    public function setPage(int $number);
    public function setTotalCount(Query $query);
    public function getPageCount();
    public function getOffset();
    public function getLimit();
    public function getPage();
}
