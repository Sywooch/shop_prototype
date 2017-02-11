<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\collections\PurchasesCollectionInterface;
use app\models\CurrencyInterface;
use app\forms\PurchaseForm;

/**
 * Коллекция базовых методов
 */
trait CartHandlerTrait
{
    /**
     * Возвращает массив конфигурации для виджета ShortCartWidget
     * @param PurchasesCollectionInterface $purchasesCollection
     * @param CurrencyInterface $currentCurrencyModel
     * @return array
     */
    private function shortCartWidgetAjaxConfig(PurchasesCollectionInterface $purchasesCollection, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['purchases'] = $purchasesCollection;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'short-cart-ajax.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CartWidget
     * @param PurchasesCollectionInterface $purchasesCollection
     * @param CurrencyInterface $currentCurrencyModel
     * @return array
     */
    private function cartWidgetConfig(PurchasesCollectionInterface $purchasesCollection, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['purchases'] = $purchasesCollection;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['updateForm'] = new PurchaseForm(['scenario'=>PurchaseForm::UPDATE]);
            $dataArray['deleteForm'] = new PurchaseForm(['scenario'=>PurchaseForm::DELETE]);
            $dataArray['header'] = \Yii::t('base', 'Selected products');
            $dataArray['template'] = 'cart.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ShortCartRedirectWidget
     * @param PurchasesCollectionInterface $purchasesCollection
     * @param CurrencyInterface $currentCurrencyModel
     * @return array
     */
    private function shortCartRedirectWidgetConfig(PurchasesCollectionInterface $purchasesCollection, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['purchases'] = $purchasesCollection;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'short-cart-redirect.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
