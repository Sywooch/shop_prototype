<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\{AdminOrdersFinder,
    AdminOrdersFiltersSessionFinder};
use app\collections\PurchasesCollection;
use app\helpers\HashHelper;

/**
 * Возвращает объект PurchasesCollection
 */
class AdminOrdersCollectionService extends AbstractBaseService
{
    /**
     * @var PurchasesCollection
     */
    private $purchasesCollection = null;
    
    /**
     * Возвращает PurchasesCollection
     * @param $request
     * @return PurchasesCollection
     */
    public function handle($request): PurchasesCollection
    {
        try {
            if (empty($this->purchasesCollection)) {
                $finder = \Yii::$app->registry->get(AdminOrdersFiltersSessionFinder::class, ['key'=>HashHelper::createHash([\Yii::$app->params['adminOrdersFilters']])]);
                $filtersModel = $finder->find();
                
                $finder = \Yii::$app->registry->get(AdminOrdersFinder::class, [
                    'page'=>$request->get(\Yii::$app->params['pagePointer']) ?? 0,
                    'filters'=>$filtersModel
                ]);
                
                $this->purchasesCollection = $finder->find();
            }
            
            return $this->purchasesCollection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
