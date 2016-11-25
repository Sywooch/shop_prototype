<?php

namespace app\filters;

use yii\base\{ErrorException,
    Object};
use app\filters\FilterInterface;
use app\exceptions\ExceptionsTrait;

class OffsetFilter extends Object implements FilterInterface
{
    use ExceptionsTrait;
    
    public $condition;
    
    public function apply($query)
    {
        try {
            $query->offset($this->condition ?? 0);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
