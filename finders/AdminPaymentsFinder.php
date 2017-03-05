<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\PaymentsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает коллекцию способов доставки из СУБД
 */
class AdminPaymentsFinder extends AbstractBaseFinder
{
    /**
     * @var массив загруженных PaymentsModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find()
    {
        try {
            if (empty($this->storage)) {
                $query = PaymentsModel::find();
                $query->select(['[[payments.id]]', '[[payments.name]]', '[[payments.description]]', '[[payments.active]]']);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
