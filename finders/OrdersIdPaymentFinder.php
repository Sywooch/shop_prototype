<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\models\PurchasesModel;

/**
 * Возвращает покупки, связанные с пользователем из СУБД
 */
class OrdersIdPaymentFinder extends AbstractBaseFinder
{
    /**
     * @var int id_payment
     */
    private $id_payment;
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
            if (empty($this->id_payment)) {
                throw new ErrorException($this->emptyError('id_payment'));
            }
            
            if (empty($this->storage)) {
                $query = PurchasesModel::find();
                $query->select(['[[purchases.id]]', '[[purchases.id_user]]', '[[purchases.id_name]]', '[[purchases.id_surname]]', '[[purchases.id_email]]', '[[purchases.id_phone]]', '[[purchases.id_address]]', '[[purchases.id_city]]', '[[purchases.id_country]]', '[[purchases.id_postcode]]', '[[purchases.id_product]]',  '[[purchases.quantity]]',  '[[purchases.id_color]]',  '[[purchases.id_size]]', '[[purchases.price]]', '[[purchases.id_payment]]',  '[[purchases.id_payment]]',  '[[purchases.received]]',  '[[purchases.received_date]]', '[[purchases.processed]]', '[[purchases.canceled]]', '[[purchases.shipped]]']);
                $query->where(['[[purchases.id_payment]]'=>$this->id_payment]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ID свойству OrdersIdPaymentFinder::id_payment
     * @param int $id_payment
     */
    public function setId_payment(int $id_payment)
    {
        try {
            $this->id_payment = $id_payment;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
