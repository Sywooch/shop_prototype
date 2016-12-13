<?php

namespace app\finders;

use app\finders\AbstractBaseFinder;
use app\models\CurrencyModel;

/**
 * Возвращает валюту по умолчанию из СУБД
 */
class MainCurrencyFinder extends AbstractBaseFinder
{
    /**
     * @var загруженный CurrencyModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
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
