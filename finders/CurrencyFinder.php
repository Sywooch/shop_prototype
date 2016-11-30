<?php

namespace app\finders;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;
use app\models\CurrencyModel;
use app\finders\FinderInterface;
use app\collections\CollectionInterface;

class CurrencyFinder extends Model implements FinderInterface
{
    use ExceptionsTrait;
    
    /**
     * @var object CollectionInterface
     */
    private $collection;
    
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find(): CollectionInterface
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                $query = CurrencyModel::find();
                $query->select(['[[currency.id]]', '[[currency.code]]']);
                $currencyArray = $query->all();
                
                if (!empty($currencyArray)) {
                    foreach ($currencyArray as $currency) {
                        $this->collection->add($currency);
                    }
                }
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству CurrencyFinder::collection
     * @param object $collection CollectionInterface
     */
    public function setCollection(CollectionInterface $collection)
    {
        try {
            $this->collection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
