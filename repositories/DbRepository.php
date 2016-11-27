<?php

namespace app\repositories;

use yii\base\ErrorException;
use yii\db\Query;
use app\repositories\{AbstractBaseRepository,
    RepositoryInterface};
use app\exceptions\ExceptionsTrait;
use app\models\CollectionInterface;
use app\filters\FilterInterface;

class DbRepository extends AbstractBaseRepository implements RepositoryInterface
{
    /**
     * @var object Query для построения запроса
     */
    private $query;
    /**
     * @var object CollectionInterface
     */
    private $collection;
    /**
     * @var object Model
     */
    private $entity;
    
    /**
     * Применяет фильтр к запросу
     * @param object $filter FilterInterface
     */
    public function setFilter(FilterInterface $filter)
    {
        try {
            if (empty($this->query)) {
                throw new ErrorException(ExceptionsTrait::emptyError('query'));
            }
            
            $filter->apply($this->query);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект yii\base\Model
     * @param mixed $request параметры для построения запроса
     * @return Model/null
     */
    public function getOne($request=null)
    {
        try {
            if (empty($this->query)) {
                throw new ErrorException(ExceptionsTrait::emptyError('query'));
            }
            
            if (empty($this->entity)) {
                $data = $this->query->one();
                if ($data !== null) {
                    $this->entity = $data;
                }
            }
            
            return !empty($this->entity) ? $this->entity : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект CollectionInterface
     * @param mixed $request параметры для построения запроса
     * @return CollectionInterface
     */
    public function getGroup($request=null): CollectionInterface
    {
        try {
            if (empty($this->query)) {
                throw new ErrorException(ExceptionsTrait::emptyError('query'));
            }
            if (empty($this->collection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                $data = $this->query->all();
                if (!empty($data)) {
                    foreach ($data as $object) {
                        $this->collection->add($object);
                    }
                }
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function saveGroup($key)
    {
    }
    
    /**
     * Присваивает CollectionInterface свойству DbRepository::collection
     * @param object $composit CollectionInterface
     */
    public function setCollection(CollectionInterface $collection)
    {
        try {
            $this->collection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект CollectionInterface
     * @return CollectionInterface/null
     */
    public function getCollection()
    {
        try {
            return !empty($this->collection) ? $this->collection : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Query свойству DbRepository::query
     * @param object $query Query
     */
    public function setQuery(Query $query)
    {
        try {
            $this->query = $query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
