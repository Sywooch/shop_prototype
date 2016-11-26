<?php

namespace app\filters;

use yii\base\{ErrorException,
    Object};
use yii\sphinx\MatchExpression;
use app\filters\FilterInterface;
use app\exceptions\ExceptionsTrait;

class MatchFilter extends Object implements FilterInterface
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
            $query->match(new MatchExpression($this->condition['fields'], $this->condition['condition']));
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
