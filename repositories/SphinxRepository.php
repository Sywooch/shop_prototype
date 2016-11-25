<?php

namespace app\repositories;

use yii\base\ErrorException;
use yii\sphinx\Query;
use app\repositories\{AbstractBaseRepository,
    RepositoryInterface};
use app\exceptions\ExceptionsTrait;
use app\models\CollectionInterface;
use app\queries\{QueryCriteria,
    CriteriaInterface};

class SphinxRepository extends AbstractBaseRepository implements RepositoryInterface
{
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
            if (empty($this->collection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                $query = new Query();
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
     * Возвращает объект CriteriaInterface для установки критериев фильтрации
     * @return object $criteria CriteriaInterface
     */
    public function getCriteria(): CriteriaInterface
    {
        try {
            if (empty($this->criteria)) {
                $this->criteria = new QueryCriteria();
            }
            return $this->criteria;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству SphinxRepository::collection
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
}
