<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\CurrencyModel;
use app\finders\AbstractBaseFinder;
use app\collections\CollectionInterface;

/**
 * Возвращает доступные валюты из СУБД
 */
class CurrencyFinder extends AbstractBaseFinder
{
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find(): CollectionInterface
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException($this->emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                $query = CurrencyModel::find();
                $query->select(['[[currency.id]]', '[[currency.code]]']);
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
