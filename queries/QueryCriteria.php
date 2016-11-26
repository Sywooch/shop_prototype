<?php

namespace app\queries;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\queries\{CriteriaInterface,
    PaginationInterface};
use app\filters\FilterInterface;

/**
 * Устанавливает и применяет критерии к SQL запросам
 */
class QueryCriteria extends Object implements CriteriaInterface
{
    use ExceptionsTrait;
    
    /**
     * @var array фильтры FilterInterface, которые будут применены к запросу
     */
    private $filters = [];
    
    /**
     * Применяет критерии к запросу
     * @param mixed $query запрос, к которому будет применена фильтрация
     */
    public function apply($query)
    {
        try {
            if (!empty($this->filters)) {
                foreach ($this->filters as $filter) {
                    $filter->apply($query);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Устанавливает фильтр, который будет применен к запросу
     * @param object $filter FilterInterface
     */
    public function setFilter(FilterInterface $filter)
    {
        try {
            $this->filters[] = $filter;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
