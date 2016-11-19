<?php

namespace app\repository;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\repository\{AbstractBaseRepository,
    GetGroupRepositoryInterface};
use app\exceptions\ExceptionsTrait;
use app\models\{CurrencyCompositInterface,
    CurrencyModel,
    QueryCriteriaInterface};

class CurrencyRepository extends AbstractBaseRepository implements GetGroupRepositoryInterface
{
    /**
     * @var object CurrencyCompositInterface
     */
    private $items = [];
    
    /**
     * Возвращает CurrencyCompositInterface, содержащий коллекцию CurrencyModel
     * @return CurrencyCompositInterface или null
     */
    public function getGroup($data=null)
    {
        try {
            if (empty($this->items)) {
                throw new ErrorException(ExceptionsTrait::emptyError('items'));
            }
            
            if ($this->items->isEmpty()) {
                $query = CurrencyModel::find();
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
     * Присваивает CurrencyCompositInterface свойству CurrencyRepository::items
     * @param object $composit CurrencyCompositInterface
     */
    public function setItems(CurrencyCompositInterface $composit)
    {
        try {
            $this->items = $composit;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
