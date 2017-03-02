<?php

namespace app\finders;

use app\models\CurrencyModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные валюты из СУБД
 */
class CurrencyExcludeIdFinder extends AbstractBaseFinder
{
    /**
     * @var int ID строки, которой не должно быть в выборке
     */
    private $id;
    /**
     * @var массив загруженных CurrencyModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->id)) {
                throw new ErrorException($this->emptyError('id'));
            }
            
            if (empty($this->storage)) {
                $query = CurrencyModel::find();
                $query->select(['[[currency.id]]', '[[currency.code]]', '[[currency.exchange_rate]]', '[[currency.main]]', '[[currency.update_date]]']);
                $query->where(['!=', '[[currency.id]]', $this->id]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение CurrencyNotBaseFinder::id
     */
    public function setId(int $id)
    {
        try {
            $this->id = $id;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
