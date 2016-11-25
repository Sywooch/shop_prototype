<?php

namespace app\filters;

interface FilterInterface
{
    public function apply($query);
}
