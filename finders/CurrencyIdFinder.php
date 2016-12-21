<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\CurrencyModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные валюты из СУБД
 */
class CurrencyIdFinder extends AbstractBaseFinder
{
    /**
     * @var int параметр, определяющий искомую валюту
     */
    public $id;
    /**
     * @var загруженный CurrencyModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     */
    public function find()
    {
        try {
             if (empty($this->storage)) {
                if (empty($this->id)) {
                    throw new ErrorException($this->emptyError('id'));
                }
                
                $query = CurrencyModel::find();
                $query->select(['[[currency.id]]', '[[currency.code]]', '[[currency.exchange_rate]]']);
                $query->where(['[[currency.id]]'=>$this->id]);
                
               $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
