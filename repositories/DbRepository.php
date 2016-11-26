<?php

namespace app\repositories;

use yii\base\ErrorException;
use yii\db\Query;
use app\repositories\{AbstractBaseRepository,
    RepositoryInterface};
use app\exceptions\ExceptionsTrait;
use app\models\CollectionInterface;
use app\queries\{QueryCriteria,
    CriteriaInterface};

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
     * @var object CriteriaInterface
     */
    private $criteria;
    
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
            if (empty($this->criteria)) {
                throw new ErrorException(ExceptionsTrait::emptyError('criteria'));
            }
            
            if (empty($this->entity)) {
                $query = $this->query;
                $query = $this->addCriteria($query);
                $data = $query->one();
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
            if (empty($this->criteria)) {
                throw new ErrorException(ExceptionsTrait::emptyError('criteria'));
            }
            
            if ($this->collection->isEmpty()) {
                $query = $this->query;
                $query = $this->addCriteria($query);
                $this->collectionConfigure($query);
                $data = $query->all();
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
    
    /**
     * Присваивает CriteriaInterface свойству DbRepository::criteria
     * @param object $criteria CriteriaInterface
     */
    public function setCriteria(CriteriaInterface $criteria)
    {
        try {
            $this->criteria = $criteria;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект CriteriaInterface для установки критериев фильтрации
     * @return CriteriaInterface/null
     */
    public function getCriteria()
    {
        try {
            return !empty($this->criteria) ? $this->criteria : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
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
    
    public function saveGroup($key)
    {
    }
    
    /**
     * Присваивает Query свойству DbRepository::query
     * @param object $criteria CriteriaInterface
     */
    public function setQuery(Query $query)
    {
        try {
            $this->query = $query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект Query
     * @return Query/null
     */
    public function getQuery()
    {
        try {
            return !empty($this->query) ? $this->query : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
