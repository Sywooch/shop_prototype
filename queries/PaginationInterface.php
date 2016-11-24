<?php

namespace app\queries;

interface PaginationInterface
{
    public function setRequest(array $request);
    public function configure($query);
}
