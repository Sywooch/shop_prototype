<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\{AccountOrdersFinder,
    OrdersFiltersSessionFinder};
use app\collections\PurchasesCollection;
use app\helpers\HashHelper;

/**
 * Возвращает объект PurchasesCollection
 */
class AccountOrdersCollectionService extends AbstractBaseService
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
            if (\Yii::$app->user->isGuest === true) {
                throw new ErrorException($this->emptyError('user'));
            }
            
            $user = \Yii::$app->user->identity;
            
            if (empty($this->purchasesCollection)) {
                $finder = \Yii::$app->registry->get(OrdersFiltersSessionFinder::class, ['key'=>HashHelper::createHash([\Yii::$app->params['ordersFilters']])]);
                $filtersModel = $finder->find();
                
                $finder = \Yii::$app->registry->get(AccountOrdersFinder::class, [
                    'id_user'=>$user->id,
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
