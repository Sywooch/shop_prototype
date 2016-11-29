<?php

namespace app\repositories;

use yii\base\ErrorException;
use yii\db\Query;
use app\repositories\{AbstractBaseRepository,
    RepositoryInterface};
use app\exceptions\ExceptionsTrait;
use app\helpers\SessionHelper;
use app\collections\CollectionInterface;
use app\queries\CriteriaInterface;

class SessionRepository extends AbstractBaseRepository implements RepositoryInterface
{
    /**
     * @var string имя класса ActiveRecord/Model
     */
    public $class;
    /**
     * @var object Query для построения запроса
     */
    public $query;
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
     * Возвращает данные из сессионного хранилища
     * @param string $key ключ доступа к данным
     * @return object/null
     */
    public function getOne($key)
    {
        try {
            if (empty($this->entity)) {
                $data = SessionHelper::read($key);
                if (!empty($data)) {
                    $this->entity = \Yii::createObject(array_merge(['class'=>$this->class], $data));
                }
            }
            
            return !empty($this->entity) ? $this->entity : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает коллекцию объектов, обернутую в CollectionInterface
     * @param string $key ключ доступа к данным
     * @return CollectionInterface/null
     */
    public function getGroup($key)
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                $data = SessionHelper::read($key);
                if (!empty($data)) {
                    foreach ($data as $item) {
                        $this->collection->add(\Yii::createObject(array_merge(['class'=>$this->class], $item)));
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
     * @return CriteriaInterface/null
     */
    public function getCriteria()
    {
        try {
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CriteriaInterface свойству SessionRepository::criteria
     * @param object $criteria CriteriaInterface
     */
    public function setCriteria(CriteriaInterface $criteria)
    {
        try {
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству SessionRepository::items
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
     * Сохраняет группу данных в сессионном хранилище
     * @param string $key ключ доступа к данным
     * @return bool
     */
    public function saveGroup($key)
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('collection'));
            }
            
            SessionHelper::write($key, $this->collection->getArray());
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Query свойству SessionRepository::query
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
