<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\DeliveriesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает DeliveriesModel из СУБД
 */
class DeliveryNameFinder extends AbstractBaseFinder
{
    /**
     * @var string имя типа доставки
     */
    private $name;
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
            if (empty($this->name)) {
                throw new ErrorException($this->emptyError('name'));
            }
            
            if (empty($this->storage)) {
                $query = DeliveriesModel::find();
                $query->select(['[[deliveries.id]]', '[[deliveries.name]]', '[[deliveries.description]]', '[[deliveries.price]]', '[[deliveries.active]]']);
                $query->where(['[[deliveries.name]]'=>$this->name]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ID свойству DeliveriesModel::name
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
