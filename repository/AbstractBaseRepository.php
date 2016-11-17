<?php

namespace app\repository;

use app\models\QueryCriteriaInterface;
use app\exceptions\ExceptionsTrait;

abstract class AbstractBaseRepository
{
    use ExceptionsTrait;
    
    protected $criteria;
    
    public function setCriteria(QueryCriteriaInterface $criteria)
    {
        try {
            $this->criteria = $criteria;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
