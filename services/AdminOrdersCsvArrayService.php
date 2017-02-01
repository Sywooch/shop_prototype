<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\{AdminOrdersCsvFinder,
    OrdersFiltersSessionFinder};
use app\helpers\HashHelper;

/**
 * Возвращает массив PurchasesModel
 */
class AdminOrdersCsvArrayService extends AbstractBaseService
{
    /**
     * @var ActiveQuery
     */
    private $ordersQuery = null;
    
    /**
     * Возвращает ActiveQuery
     * @param $request
     * @return ActiveQuery
     */
    public function handle($request=null)
    {
        try {
            if (empty($this->ordersQuery)) {
                $finder = \Yii::$app->registry->get(OrdersFiltersSessionFinder::class, ['key'=>HashHelper::createHash([\Yii::$app->params['ordersFilters']])]);
                $filtersModel = $finder->find();
                
                $finder = \Yii::$app->registry->get(AdminOrdersCsvFinder::class, [
                    'filters'=>$filtersModel
                ]);
                
                $this->ordersQuery = $finder->find();
            }
            
            return $this->ordersQuery;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
