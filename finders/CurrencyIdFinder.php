<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\CurrencyModel;
use app\finders\AbstractBaseFinder;
use app\collections\CollectionInterface;

/**
 * Возвращает доступные валюты из СУБД
 */
class CurrencyIdFinder extends AbstractBaseFinder
{
    /**
     * @var int GET параметр, определяющий искомую валюту
     */
    public $id;
    
    public function rules()
    {
        return [
            [['id'], 'required']
        ];
    }
    
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
                if ($this->validate() === false) {
                    throw new ErrorException($this->modelError($this->errors));
                }
                
                $query = CurrencyModel::find();
                $query->select(['[[currency.id]]', '[[currency.code]]', '[[currency.exchange_rate]]']);
                $query->where(['[[currency.id]]'=>$this->id]);
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
