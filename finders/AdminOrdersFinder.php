<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\{AbstractBaseFinder,
    AdminOrdersFiltersSessionFinder};
use app\models\PurchasesModel;
use app\collections\{LightPagination,
    PurchasesCollection};
use app\helpers\HashHelper;
use app\filters\AdminOrdersFiltersInterface;

/**
 * Возвращает заказы из СУБД
 */
class AdminOrdersFinder extends AbstractBaseFinder
{
    /**
     * @var AdminOrdersFiltersInterface
     */
    private $filters;
    /**
     * @var string GET параметр, определяющий текущую страницу
     */
    private $page;
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
            if (empty($this->filters)) {
                throw new ErrorException($this->emptyError('filters'));
            }
            
            if (empty($this->storage)) {
                $this->storage = new PurchasesCollection(['pagination'=>new LightPagination()]);
                
                $query = PurchasesModel::find();
                $query->select(['[[purchases.id]]', '[[purchases.id_user]]', '[[purchases.id_name]]', '[[purchases.id_surname]]', '[[purchases.id_email]]', '[[purchases.id_phone]]', '[[purchases.id_address]]', '[[purchases.id_city]]', '[[purchases.id_country]]', '[[purchases.id_postcode]]', '[[purchases.id_product]]',  '[[purchases.quantity]]', '[[purchases.id_color]]', '[[purchases.id_size]]', '[[purchases.price]]', '[[purchases.id_delivery]]', '[[purchases.id_payment]]', '[[purchases.received]]', '[[purchases.received_date]]', '[[purchases.processed]]', '[[purchases.canceled]]', '[[purchases.shipped]]']);
                $query->with('product', 'color', 'size', 'name', 'surname', 'address', 'city', 'country', 'postcode', 'phone', 'payment', 'delivery');
                
                if (!empty($this->filters->getStatus())) {
                    $query->where([sprintf('[[purchases.%s]]', $this->filters->getStatus())=>true]);
                    foreach (\Yii::$app->params['orderStatuses'] as $status) {
                        if ($status !== $this->filters->getStatus() && $status !== 'received') {
                            $query->andWhere([sprintf('[[purchases.%s]]', $status)=>false]);
                        }
                    }
                }
                
                $this->storage->pagination->pageSize = \Yii::$app->params['limit'];
                $this->storage->pagination->page = !empty($this->page) ? (int) $this->page - 1 : 0;
                $this->storage->pagination->setTotalCount($query);
                
                $query->offset($this->storage->pagination->offset);
                $query->limit($this->storage->pagination->limit);
                
                $sortingField = \Yii::$app->params['sortingFieldOrders'];
                $sortingType = $this->filters->getSortingType() ?? \Yii::$app->params['sortingTypeOrders'];
                $query->orderBy([sprintf('[[purchases.%s]]', $sortingField)=>(int) $sortingType]);
                
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
    
    /**
     * Присваивает AdminOrdersFiltersInterface ProductsFinder::filters
     * @param AdminOrdersFiltersInterface $filters
     */
    public function setFilters(AdminOrdersFiltersInterface $filters)
    {
        try {
            $this->filters = $filters;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает номер страницы ProductsFinder::page
     * @param string $page
     */
    public function setPage(string $page)
    {
        try {
            $this->page = $page;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}