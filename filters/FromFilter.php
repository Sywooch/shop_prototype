<?php

namespace app\filters;

use yii\base\{ErrorException,
    Object};
use app\filters\FilterInterface;
use app\exceptions\ExceptionsTrait;

class FromFilter extends Object implements FilterInterface
{
    use ExceptionsTrait;
    
    private $condition;
    
    public function __construct($condition, $config=[])
    {
        try {
            $this->condition = $condition;
            
            parent::__construct($config);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function apply($query)
    {
        try {
            $query->from($this->condition);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
