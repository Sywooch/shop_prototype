<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\DeliveriesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает коллекцию способов доставки из СУБД
 */
class AdminDeliveriesFinder extends AbstractBaseFinder
{
    /**
     * @var массив загруженных DeliveriesModel
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
                $query = DeliveriesModel::find();
                $query->select(['[[deliveries.id]]', '[[deliveries.name]]', '[[deliveries.description]]', '[[deliveries.price]]', '[[deliveries.active]]']);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
