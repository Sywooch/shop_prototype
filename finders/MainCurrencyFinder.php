<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\CurrencyModel;
use app\collections\CollectionInterface;

/**
 * Возвращает валюту по умолчанию из СУБД
 */
class MainCurrencyFinder extends AbstractBaseFinder
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
                $query->select(['[[currency.id]]', '[[currency.code]]', '[[currency.exchange_rate]]', '[[currency.main]]']);
                $query->where(['[[currency.main]]'=>true]);
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
