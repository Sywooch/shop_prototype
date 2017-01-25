<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\models\PurchasesModel;
use app\collections\{LightPagination,
    PurchasesCollection};

/**
 * Возвращает заказы из СУБД
 */
class PurchasesFinder extends AbstractBaseFinder
{
    /**
     * @var string GET параметр, определяющий текущую страницу
     */
    public $page;
    /**
     * @var PurchasesCollection
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return PurchasesCollection
     */
    public function find(): PurchasesCollection
    {
        try {
            if (empty($this->storage)) {
                $this->storage = new PurchasesCollection(['pagination'=>new LightPagination()]);
                
                $query = PurchasesModel::find();
                $query->select(['[[purchases.id]]', '[[purchases.id_user]]', '[[purchases.id_name]]', '[[purchases.id_surname]]', '[[purchases.id_email]]', '[[purchases.id_phone]]', '[[purchases.id_address]]', '[[purchases.id_city]]', '[[purchases.id_country]]', '[[purchases.id_postcode]]', '[[purchases.id_product]]',  '[[purchases.quantity]]',  '[[purchases.id_color]]',  '[[purchases.id_size]]', '[[purchases.price]]', '[[purchases.id_delivery]]',  '[[purchases.id_payment]]',  '[[purchases.received]]',  '[[purchases.received_date]]', '[[purchases.processed]]', '[[purchases.canceled]]', '[[purchases.shipped]]']);
                $query->with('product', 'color', 'size', 'name', 'surname', 'address', 'city', 'country', 'postcode', 'phone', 'payment', 'delivery');
                
                $this->storage->pagination->pageSize = \Yii::$app->params['limit'];
                $this->storage->pagination->page = !empty($this->page) ? (int) $this->page - 1 : 0;
                $this->storage->pagination->setTotalCount($query);
                
                $query->offset($this->storage->pagination->offset);
                $query->limit($this->storage->pagination->limit);
                
                //$sortingType = $this->filters->sortingType ?? \Yii::$app->params['sortingType'];
                $sortingField = \Yii::$app->params['sortingFieldOrders'];
                $sortingType = \Yii::$app->params['sortingTypeOrders'];
                $query->orderBy(['[[purchases.' . $sortingField . ']]'=>(int) $sortingType]);
                
                $purchasesModelArray = $query->all();
                
                if (!empty($purchasesModelArray)) {
                    foreach ($purchasesModelArray as $purchase) {
                        $this->storage->addRaw($purchase);
                    }
                }
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
