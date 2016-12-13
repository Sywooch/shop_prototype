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
    private $storage;
    
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find()
    {
        try {
            /*if (empty($this->collection)) {
                throw new ErrorException($this->emptyError('collection'));
            }*/
            
            if (empty($this->storage)) {
                $query = CurrencyModel::find();
                $query->select(['[[currency.id]]', '[[currency.code]]', '[[currency.exchange_rate]]', '[[currency.main]]']);
                $query->where(['[[currency.main]]'=>true]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
