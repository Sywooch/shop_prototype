<?php

namespace app\repositories;

use yii\base\ErrorException;
use app\repositories\{AbstractBaseRepository,
    RepositoryInterface};
use app\exceptions\ExceptionsTrait;
use app\models\{CollectionInterface,
    QueryCriteria,
    CriteriaInterface};

class DbRepository extends AbstractBaseRepository implements RepositoryInterface
{
    /**
     * @var string имя класса ActiveRecord для построения запроса
     */
    public $class;
    /**
     * @var object CollectionInterface
     */
    private $collection;
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
            if (empty($this->collection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('collection'));
            }
            
            if (empty($this->item)) {
                $query = $this->class::find();
                $query = $this->addCriteria($query);
                $data = $query->one();
                if ($data !== null) {
                    $this->collection->addOne($data);
                }
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект CollectionInterface
     * @param mixed $request параметры для построения запроса
     * @return CollectionInterface
     */
    public function getGroup($request=null)
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                $query = $this->class::find();
                $query = $this->addCriteria($query);
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
    
    public function saveGroup($key)
    {
    }
}
