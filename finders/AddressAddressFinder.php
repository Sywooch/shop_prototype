<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\AddressModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает AddressModel из СУБД
 */
class AddressAddressFinder extends AbstractBaseFinder
{
    /**
     * @var string address
     */
    public $address;
    /**
     * @var AddressModel
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
                if (empty($this->address)) {
                    throw new ErrorException($this->emptyError('address'));
                }
                
                $query = AddressModel::find();
                $query->select(['[[address.id]]', '[[address.address]]']);
                $query->where(['[[address.address]]'=>$this->address]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
