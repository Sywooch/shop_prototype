<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;
use app\models\CurrencyInterface;
use app\finders\{CategoriesFinder,
    PurchasesSessionFinder};
use app\collections\PurchasesCollectionInterface;

/**
 * Коллекция базовых методов
 */
trait BaseHandlerTrait
{
    /**
     * Возвращает CurrencyInterface объект текущей валюты
     * @return CurrencyInterface
     */
    private function getCurrentCurrency(): CurrencyInterface
    {
        try {
            $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                'key'=>HashHelper::createCurrencyKey()
            ]);
            $currentCurrencyModel = $service->get();
            
            return $currentCurrencyModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает PurchasesCollectionInterface, коллекцию заказов
     * @return PurchasesCollectionInterface
     */
    private function getOrdersSessionCollection(): PurchasesCollectionInterface
    {
        try {
            $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                'key'=>HashHelper::createCartKey()
            ]);
            $ordersCollection = $finder->find();
            
            return $ordersCollection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив CategoriesModel
     * @return array
     */
    private function getCategoriesModelArray(): array
    {
        try {
            $finder = \Yii::$app->registry->get(CategoriesFinder::class);
            $categoriesModelArray = $finder->find();
            
            if (empty($categoriesModelArray)) {
                throw new ErrorException($this->emptyError('categoriesModelArray'));
            }
            
            return $categoriesModelArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
