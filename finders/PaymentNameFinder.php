<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\PaymentsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает PaymentsModel из СУБД
 */
class PaymentNameFinder extends AbstractBaseFinder
{
    /**
     * @var string имя формы оплаты
     */
    private $name;
    /**
     * @var загруженный PaymentsModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find()
    {
        try {
            if (empty($this->name)) {
                throw new ErrorException($this->emptyError('name'));
            }
            
            if (empty($this->storage)) {
                $query = PaymentsModel::find();
                $query->select(['[[payments.id]]', '[[payments.name]]', '[[payments.description]]', '[[payments.active]]']);
                $query->where(['[[payments.name]]'=>$this->name]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение PaymentNameFinder::name
     * @param string $name
     */
    public function setName(string $name)
    {
        try {
            $this->name = $name;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
