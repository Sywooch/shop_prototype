<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\models\PurchasesModel;
use app\collections\{LightPagination,
    PurchasesCollection};
use app\filters\OrdersFiltersInterface;
use app\helpers\DateHelper;

/**
 * Возвращает заказы из СУБД
 */
class AccountOrdersFinder extends AbstractBaseFinder
{
    /**
     * @var int id_user
     */
    private $id_user;
    /**
     * @var OrdersFiltersInterface
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
            if (empty($this->id_user)) {
                throw new ErrorException($this->emptyError('id_user'));
            }
            if (empty($this->filters)) {
                throw new ErrorException($this->emptyError('filters'));
            }
            
            if (empty($this->storage)) {
                $this->storage = new PurchasesCollection(['pagination'=>new LightPagination()]);
                
                $query = PurchasesModel::find();
                $query->select(['[[purchases.id]]', '[[purchases.id_user]]', '[[purchases.id_name]]', '[[purchases.id_surname]]', '[[purchases.id_email]]', '[[purchases.id_phone]]', '[[purchases.id_address]]', '[[purchases.id_city]]', '[[purchases.id_country]]', '[[purchases.id_postcode]]', '[[purchases.id_product]]',  '[[purchases.quantity]]', '[[purchases.id_color]]', '[[purchases.id_size]]', '[[purchases.price]]', '[[purchases.id_delivery]]', '[[purchases.id_payment]]', '[[purchases.received]]', '[[purchases.received_date]]', '[[purchases.processed]]', '[[purchases.canceled]]', '[[purchases.shipped]]']);
                $query->where(['[[purchases.id_user]]'=>$this->id_user]);
                
                /*switch ((int) $this->filters->getDatesInterval()) {
                    case 2:
                        break;
                    case 3:
                        $query->where(['>', '[[purchases.received_date]]', DateHelper::getDaysAgo00(3)]);
                        break;
                    case 4:
                        $query->where(['>', '[[purchases.received_date]]', DateHelper::getDaysAgo00(1)]);
                        break;
                    default:
                        $query->where(['>', '[[purchases.received_date]]', DateHelper::getToday00()]);
                }*/
                
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
     * Присваивает ID пользователя AccountOrdersFinder::id_user
     * @param int $id_user
     */
    public function setId_user(int $id_user)
    {
        try {
            $this->id_user = $id_user;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает OrdersFiltersInterface AccountOrdersFinder::filters
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
    
    /**
     * Присваивает номер страницы AccountOrdersFinder::page
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
