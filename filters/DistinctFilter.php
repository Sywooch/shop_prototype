<?php

namespace app\filters;

use yii\base\{ErrorException,
    Object};
use app\filters\FilterInterface;
use app\exceptions\ExceptionsTrait;

class DistinctFilter extends Object implements FilterInterface
{
    use ExceptionsTrait;
    
    public function apply($query)
    {
        try {
            $query->distinct();
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
