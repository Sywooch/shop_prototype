<?php

namespace app\filters;

use yii\base\{ErrorException,
    Object};
use app\filters\FilterInterface;
use app\exceptions\ExceptionsTrait;

class SortingFilter extends Object implements FilterInterface
{
    use ExceptionsTrait;
    
    public function apply($query)
    {
        try {
            $field = 'date';
            $type = SORT_DESC;
            $query->orderBy(['[[' . $field . ']]'=>$type]);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
