<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\PaymentsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает PaymentsModel из СУБД
 */
class PaymentIdFinder extends AbstractBaseFinder
{
    /**
     * @var int Id
     */
    public $id;
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
            if (empty($this->id)) {
                throw new ErrorException($this->emptyError('id'));
            }
            
            if (empty($this->storage)) {
                $query = PaymentsModel::find();
                $query->select(['[[payments.id]]', '[[payments.name]]', '[[payments.description]]']);
                $query->where(['[[payments.id]]'=>$this->id]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
