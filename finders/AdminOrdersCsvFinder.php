<?php

namespace app\finders;

use yii\base\ErrorException;
use yii\db\ActiveQuery;
use app\finders\AbstractBaseFinder;
use app\models\PurchasesModel;
use app\helpers\DateHelper;
use app\filters\OrdersFiltersInterface;

/**
 * Возвращает заказы из СУБД
 */
class AdminOrdersCsvFinder extends AbstractBaseFinder
{
    /**
     * @var OrdersFiltersInterface
     */
    private $filters;
    /**
     * @var PurchasesCollection
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return ActiveQuery
     */
    public function find(): ActiveQuery
    {
        try {
            if (empty($this->filters)) {
                throw new ErrorException($this->emptyError('filters'));
            }
            
            if (empty($this->storage)) {
                $query = PurchasesModel::find();
                $query->select(['[[purchases.id]]', '[[purchases.id_user]]', '[[purchases.id_name]]', '[[purchases.id_surname]]', '[[purchases.id_email]]', '[[purchases.id_phone]]', '[[purchases.id_address]]', '[[purchases.id_city]]', '[[purchases.id_country]]', '[[purchases.id_postcode]]', '[[purchases.id_product]]',  '[[purchases.quantity]]', '[[purchases.id_color]]', '[[purchases.id_size]]', '[[purchases.price]]', '[[purchases.id_delivery]]', '[[purchases.id_payment]]', '[[purchases.received]]', '[[purchases.received_date]]', '[[purchases.processed]]', '[[purchases.canceled]]', '[[purchases.shipped]]']);
                $query->with('email', 'product', 'color', 'size', 'name', 'surname', 'address', 'city', 'country', 'postcode', 'phone', 'payment', 'delivery');
                
                if (!empty($this->filters->getStatus())) {
                    $query->where([sprintf('[[purchases.%s]]', $this->filters->getStatus())=>true]);
                    foreach (\Yii::$app->params['orderStatuses'] as $status) {
                        if ($status !== $this->filters->getStatus() && $status !== 'received') {
                            $query->andWhere([sprintf('[[purchases.%s]]', $status)=>false]);
                        }
                    }
                }
                
                $dateFrom = $this->filters->getDateFrom() ?? DateHelper::getToday00();
                $dateTo = ($this->filters->getDateTo() ?? DateHelper::getToday00()) + (60 * 60 * 24);
                
                $query->andWhere(['and', 
                    ['>', '[[purchases.received_date]]', $dateFrom], 
                    ['<', '[[purchases.received_date]]', $dateTo]
                ]);
                
                $sortingField = \Yii::$app->params['sortingFieldOrders'];
                $sortingType = $this->filters->getSortingType() ?? \Yii::$app->params['sortingTypeOrders'];
                $query->orderBy([sprintf('[[purchases.%s]]', $sortingField)=>(int) $sortingType]);
                
                $this->storage = $query;
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает OrdersFiltersInterface ProductsFinder::filters
     * @param OrdersFiltersInterface $filters
     */
    public function setFilters(OrdersFiltersInterface $filters)
    {
        try {
            $this->filters = $filters;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
