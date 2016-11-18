<?php

namespace app\repository;

use yii\base\Object;
use yii\db\Query;
use app\models\QueryCriteriaInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Содержит общую функциональтность для классов репозиториев
 */
abstract class AbstractBaseRepository extends Object
{
    use ExceptionsTrait;
    
    /**
     * @var object QueryCriteriaInterface
     */
    protected $criteria;
    
    /**
     * Устанавливает критерии, которые будут применены к запросу
     * @param object $criteria QueryCriteriaInterface
     */
    public function setCriteria(QueryCriteriaInterface $criteria)
    {
        try {
            $this->criteria = $criteria;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Применяет критерии к запросу
     * @param object $query Query
     */
    protected function addCriteria(Query $query)
    {
        try {
            if (!empty($this->criteria)) {
                $query = $this->criteria->filter($query);
            }
            return $query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
