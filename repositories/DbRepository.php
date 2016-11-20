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
    private $items;
    /**
     * @var object Model
     */
    private $item;
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
            if (empty($this->item)) {
                $query = $this->class::find();
                $query = $this->addCriteria($query);
                $data = $query->one();
                if ($data !== null) {
                    $this->item = $data;
                }
            }
            
            return !empty($this->item) ? $this->item : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект CollectionInterface
     * @param mixed $request параметры для построения запроса
     * @return CollectionInterface/null
     */
    public function getGroup($request=null)
    {
        try {
            if (empty($this->items)) {
                throw new ErrorException(ExceptionsTrait::emptyError('items'));
            }
            
            if ($this->items->isEmpty()) {
                $query = $this->class::find();
                $query = $this->addCriteria($query);
                $data = $query->all();
                if (!empty($data)) {
                    foreach ($data as $object) {
                        $this->items->add($object);
                    }
                }
            }
            
            return ($this->items->isEmpty() === false) ? $this->items : null;
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
     * Присваивает CollectionInterface свойству DbRepository::items
     * @param object $composit CollectionInterface
     */
    public function setItems(CollectionInterface $composit)
    {
        try {
            $this->items = $composit;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
