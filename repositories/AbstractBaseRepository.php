<?php

namespace app\repositories;

use yii\base\Object;
use app\exceptions\ExceptionsTrait;

/**
 * Содержит общую функциональтность для классов репозиториев
 */
abstract class AbstractBaseRepository extends Object
{
    use ExceptionsTrait;
    
    /**
     * Применяет критерии к запросу
     * @param mixed $query запрос, к которому будет применена фильтрация
     */
    public function addCriteria($query)
    {
        try {
            if (!empty($this->criteria)) {
                $query = $this->criteria->apply($query);
            }
            return $query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Настраивает объект коллекции
     * @param mixed $query
     */
    public function collectionConfigure($query)
    {
        try {
            if (!empty($this->collection->pagination)) {
                $this->collection->pagination->configure($query);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
