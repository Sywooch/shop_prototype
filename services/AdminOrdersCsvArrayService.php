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
     * @var array PurchasesModel
     */
    private $purchasesArray = null;
    
    /**
     * Возвращает array PurchasesModel
     * @param $request
     * @return array PurchasesModel
     */
    public function handle($request=null)
    {
        try {
            if (empty($this->purchasesArray)) {
                $finder = \Yii::$app->registry->get(OrdersFiltersSessionFinder::class, ['key'=>HashHelper::createHash([\Yii::$app->params['ordersFilters']])]);
                $filtersModel = $finder->find();
                
                $finder = \Yii::$app->registry->get(AdminOrdersCsvFinder::class, [
                    'filters'=>$filtersModel
                ]);
                
                $this->purchasesArray = $finder->find();
            }
            
            return $this->purchasesArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
