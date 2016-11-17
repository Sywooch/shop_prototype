<?php

namespace app\models;

use yii\base\{ErrorException,
    Object};
use yii\db\Query;
use app\exceptions\ExceptionsTrait;
use app\models\QueryCriteriaInterface;

class QueryCriteria extends Object implements QueryCriteriaInterface
{
    use ExceptionsTrait;
    
    private $criteriaArray = [];
    private $query;
    
    public function select(array $condition)
    {
        try {
           $this->criteriaArray[] = function() use ($condition) {
                return $this->query->select($condition);
            };
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function asArray()
    {
        try {
           $this->criteriaArray[] = function() {
                return $this->query->asArray();
            };
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function filter(Query $query)
    {
        try {
            $this->query = $query;
            foreach ($this->criteriaArray as $criteria) {
                $criteria();
            }
            return $this->query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
