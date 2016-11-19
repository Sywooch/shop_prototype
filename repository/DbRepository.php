<?php

namespace app\repository;

use yii\base\ErrorException;
use app\repository\{AbstractBaseRepository,
    DbRepositoryInterface};
use app\exceptions\ExceptionsTrait;
use app\models\CollectionInterface;

class DbRepository extends AbstractBaseRepository implements DbRepositoryInterface
{
    /**
     * @var string имя класса ActiveRecord
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
     * Возвращает объект yii\base\Model
     * @return object/null
     */
    public function getOne()
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
     * @return CollectionInterface/null
     */
    public function getGroup()
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
            
            return !empty($data) ? $this->items : null;
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
