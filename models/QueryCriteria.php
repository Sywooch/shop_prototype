<?php

namespace app\models;

use yii\base\{ErrorException,
    Object};
use yii\db\Query;
use app\exceptions\ExceptionsTrait;
use app\models\QueryCriteriaInterface;

/**
 * Устанавливает и применяет критерии к SQL запросам
 */
class QueryCriteria extends Object implements QueryCriteriaInterface
{
    use ExceptionsTrait;
    
    /**
     * @var array массив callback функций, представляющих различные модификаторы запроса
     */
    private $criteriaArray = [];
    /**
     * @var object объект запроса
     */
    private $query;
    
    /**
     * Применяет критерии к запросу, возвращает объект Query с примененными 
     * ограничениями или расширениями
     * @param $query Query
     * @return Query
     */
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
    
    /**
     * Ограничивает перечень полей запроса
     * @param array список полей
     * @return Query
     */
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
    
    /**
     * Устанавливает массив в качестве типа возвращаемых данных
     * @return Query
     */
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
    
    /**
     * Устанавливает имена связей, данные которые необходимо загрузить вместе с основным запросом
     * @param $condition имена связей
     * @return Query
     */
    public function with($condition)
    {
        try {
           $this->criteriaArray[] = function() use ($condition) {
                return $this->query->with($condition);
            };
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Устанавливает условие WHERE
     * @param array $condition условия
     * @return Query
     */
    public function where(array $condition)
    {
        try {
           $this->criteriaArray[] = function() use ($condition) {
                return $this->query->andWhere($condition);
            };
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Устанавливает условие JOIN
     * @param string $type тип объединения, например, 'INNER JOIN', 'LEFT JOIN'
     * @param string $table имя таблицы
     * @param string $condition условие объединения, то есть фрагмент ON
     * @return Query
     */
    public function join($type, $table, $condition)
    {
        try {
           $this->criteriaArray[] = function() use ($type, $table, $condition) {
                return $this->query->join($type, $table, $condition);
            };
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Устанавливает условие DISTINCT
     * @return Query
     */
    public function distinct()
    {
        try {
           $this->criteriaArray[] = function() {
                return $this->query->distinct();
            };
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Устанавливает условие LIMIT
     * @param int $condition количество возвращаемых записей
     * @return Query
     */
    public function limit(int $condition)
    {
        try {
           $this->criteriaArray[] = function() use ($condition) {
                return $this->query->limit($condition);
            };
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
