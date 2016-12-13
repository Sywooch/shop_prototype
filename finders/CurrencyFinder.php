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
                $query->select(['[[currency.id]]', '[[currency.code]]']);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
