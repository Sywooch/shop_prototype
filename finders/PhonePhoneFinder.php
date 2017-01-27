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
    private $phone;
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
            if (empty($this->phone)) {
                throw new ErrorException($this->emptyError('phone'));
            }
            
            if (empty($this->storage)) {
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
    
    /**
     * Присваивает номер телефона свойству PhonePhoneFinder::phone
     * @param string $phone
     */
    public function setPhone(string $phone)
    {
        try {
            $this->phone = $phone;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
