<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\PurchasesFinder;
use app\collections\PurchasesCollection;

/**
 * Возвращает объект PurchasesCollection
 */
class GetPurchasesCollectionService extends AbstractBaseService
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
                $finder = \Yii::$app->registry->get(PurchasesFinder::class, [
                    'page'=>$request->get(\Yii::$app->params['pagePointer']) ?? 0,
                ]);
                
                $this->purchasesCollection = $finder->find();
            }
            
            return $this->purchasesCollection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
