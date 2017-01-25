<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\models\PurchasesModel;

/**
 * Возвращает покупки, связанные с пользователем из СУБД
 */
class PurchasesIdUserFinder extends AbstractBaseFinder
{
    /**
     * @var int id_user
     */
    public $id_user;
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
            if (empty($this->storage)) {
                if (empty($this->id_user)) {
                    throw new ErrorException($this->emptyError('id_user'));
                }
                
                $query = PurchasesModel::find();
                $query->select(['[[purchases.id]]', '[[purchases.id_user]]', '[[purchases.id_name]]', '[[purchases.id_surname]]', '[[purchases.id_email]]', '[[purchases.id_phone]]', '[[purchases.id_address]]', '[[purchases.id_city]]', '[[purchases.id_country]]', '[[purchases.id_postcode]]', '[[purchases.id_product]]',  '[[purchases.quantity]]',  '[[purchases.id_color]]',  '[[purchases.id_size]]', '[[purchases.price]]', '[[purchases.id_delivery]]',  '[[purchases.id_payment]]',  '[[purchases.received]]',  '[[purchases.received_date]]', '[[purchases.processed]]', '[[purchases.canceled]]', '[[purchases.shipped]]']);
                $query->with('product', 'color', 'size', 'name', 'surname', 'address', 'city', 'country', 'postcode', 'phone', 'payment', 'delivery');
                $query->where(['[[purchases.id_user]]'=>$this->id_user]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
