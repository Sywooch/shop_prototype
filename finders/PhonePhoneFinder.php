<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\PhonesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает PhonesModel из СУБД
 */
class PhonePhoneFinder extends AbstractBaseFinder
{
    /**
     * @var string phone
     */
    public $phone;
    /**
     * @var PhonesModel
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
                if (empty($this->phone)) {
                    throw new ErrorException($this->emptyError('phone'));
                }
                
                $query = PhonesModel::find();
                $query->select(['[[phones.id]]', '[[phones.phone]]']);
                $query->where(['[[phones.phone]]'=>$this->phone]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
