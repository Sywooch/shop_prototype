<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\models\PurchasesModel;

/**
 * Возвращает покупки, связанные с пользователем из СУБД
 */
class OrdersIdDeliveryFinder extends AbstractBaseFinder
{
    /**
     * @var int id_delivery
     */
    private $id_delivery;
    /**
     * @var array массив загруженных PurchasesModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->id_delivery)) {
                throw new ErrorException($this->emptyError('id_delivery'));
            }
            
            if (empty($this->storage)) {
                $query = PurchasesModel::find();
                $query->select(['[[purchases.id]]', '[[purchases.id_user]]', '[[purchases.id_name]]', '[[purchases.id_surname]]', '[[purchases.id_email]]', '[[purchases.id_phone]]', '[[purchases.id_address]]', '[[purchases.id_city]]', '[[purchases.id_country]]', '[[purchases.id_postcode]]', '[[purchases.id_product]]',  '[[purchases.quantity]]',  '[[purchases.id_color]]',  '[[purchases.id_size]]', '[[purchases.price]]', '[[purchases.id_delivery]]',  '[[purchases.id_payment]]',  '[[purchases.received]]',  '[[purchases.received_date]]', '[[purchases.processed]]', '[[purchases.canceled]]', '[[purchases.shipped]]']);
                $query->where(['[[purchases.id_delivery]]'=>$this->id_delivery]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ID свойству OrdersIdDeliveryFinder::id_delivery
     * @param int $id_delivery
     */
    public function setId_delivery(int $id_delivery)
    {
        try {
            $this->id_delivery = $id_delivery;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
