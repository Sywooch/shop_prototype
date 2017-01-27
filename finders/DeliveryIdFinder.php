<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\DeliveriesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает DeliveriesModel из СУБД
 */
class DeliveryIdFinder extends AbstractBaseFinder
{
    /**
     * @var int Id
     */
    private $id;
    /**
     * @var загруженный DeliveriesModel
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
                $query = DeliveriesModel::find();
                $query->select(['[[deliveries.id]]', '[[deliveries.name]]', '[[deliveries.description]]', '[[deliveries.price]]']);
                $query->where(['[[deliveries.id]]'=>$this->id]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ID свойству DeliveriesModel::id
     * @param int $id
     */
    public function setId(int $id)
    {
        try {
            $this->id = $id;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
