<?php

namespace app\finders;

use app\models\CurrencyModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные валюты из СУБД
 */
class CurrencyFinder extends AbstractBaseFinder
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
                $query->select(['[[currency.id]]', '[[currency.code]]']);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
