<?php

namespace app\repositories;

use yii\base\ErrorException;
use app\repositories\{AbstractBaseRepository,
    RepositoryInterface};
use app\exceptions\ExceptionsTrait;
use app\helpers\SessionHelper;
use app\models\CollectionInterface;

class SessionRepository extends AbstractBaseRepository implements RepositoryInterface
{
    /**
     * @var string имя класса ActiveRecord/Model
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
     * @return object/null
     */
    public function getOne($key=null)
    {
        try {
            if (empty($this->item)) {
                $data = SessionHelper::read($key);
                if (!empty($data)) {
                    $this->item = \Yii::createObject(array_merge(['class'=>$this->class], $data));
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
    public function getGroup($key=null)
    {
        try {
            if (empty($this->items)) {
                throw new ErrorException(ExceptionsTrait::emptyError('items'));
            }
            
            if ($this->items->isEmpty()) {
                $data = SessionHelper::read($key);
                if (!empty($data)) {
                    foreach ($data as $item) {
                        $this->items->add(\Yii::createObject(array_merge(['class'=>$this->class], $item)));
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
    public function getCriteria()
    {
       
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
