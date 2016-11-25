<?php

namespace app\filters;

use yii\base\{ErrorException,
    Object};
use app\filters\FilterInterface;
use app\exceptions\ExceptionsTrait;

class SelectFilter extends Object implements FilterInterface
{
    use ExceptionsTrait;
    
    public $condition;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->condition)) {
                throw new ErrorException(ExceptionsTrait::emptyError('condition'));
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function apply($query)
    {
        try {
            $query->select($this->condition);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
