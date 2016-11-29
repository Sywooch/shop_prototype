<?php

namespace app\queries;

interface PaginationInterface
{
    public function setPageSize(int $size);
    public function setPage(int $number);
    public function setTotalCount($query);
    public function getPageCount();
    public function getOffset();
    public function getLimit();
    public function getPage();
}
