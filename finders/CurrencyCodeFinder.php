<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\CurrencyModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные валюты из СУБД
 */
class CurrencyCodeFinder extends AbstractBaseFinder
{
    /**
     * @var staring код валюты
     */
    private $code;
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
            if (empty($this->code)) {
                throw new ErrorException($this->emptyError('code'));
            }
            
             if (empty($this->storage)) {
                $query = CurrencyModel::find();
                $query->select(['[[currency.id]]', '[[currency.code]]', '[[currency.exchange_rate]]', '[[currency.main]]', '[[currency.update_date]]']);
                $query->where(['[[currency.code]]'=>$this->code]);
                
               $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение CurrencyCodeFinder::code
     * @param string $code
     */
    public function setCode(string $code)
    {
        try {
            $this->code = $code;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
