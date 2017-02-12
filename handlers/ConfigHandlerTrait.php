<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\web\User;
use app\models\CurrencyInterface;
use app\forms\AbstractBaseForm;
use app\collections\PurchasesCollectionInterface;

/**
 * Коллекция базовых методов
 */
trait ConfigHandlerTrait
{
    /**
     * Возвращает массив конфигурации для виджета UserInfoWidget
     * @param yii\web\User $user
     * @return array
     */
    private function userInfoWidgetConfig(User $webUser): array
    {
        try {
            $dataArray = [];
            
            $dataArray['user'] = $webUser;
            $dataArray['template'] = 'user-info.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ShortCartWidget
     * @patram PurchasesCollectionInterface $ordersCollection
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function shortCartWidgetConfig(PurchasesCollectionInterface $ordersCollection, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['purchases'] = $ordersCollection;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'short-cart.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CurrencyWidget
     * @param array $currencyArray массив доступных валют
     * @param AbstractBaseForm $changeCurrencyForm
     * @return array
     */
    private function currencyWidgetConfig(array $currencyArray, AbstractBaseForm $changeCurrencyForm): array
    {
        try {
            $dataArray = [];
            
            ArrayHelper::multisort($currencyArray, 'code');
            $dataArray['currency'] = ArrayHelper::map($currencyArray, 'id', 'code');
            
            $dataArray['form'] = $changeCurrencyForm;
            $dataArray['header'] = \Yii::t('base', 'Currency');
            $dataArray['template'] = 'currency-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета SearchWidget
     * @param string $searchKey искомая фраза
     * @return array
     */
    private function searchWidgetConfig(string $searchKey=''): array
    {
        try {
            $dataArray = [];
            
            $dataArray['text'] = $searchKey;
            $dataArray['template'] = 'search.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CategoriesMenuWidget
     * @param array $categoriesModelArray
     * @return array
     */
    private function categoriesMenuWidgetConfig(array $categoriesModelArray): array
    {
        try {
            $dataArray = [];
            
            $dataArray['categories'] = $categoriesModelArray;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountMailingsUnsubscribeWidget
     * @param array $mailingsArray
     * @param AbstractBaseForm $mailingForm
     * @return array
     */
    private function accountMailingsUnsubscribeWidgetConfig(array $mailingsArray, AbstractBaseForm $mailingForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['mailings'] = $mailingsArray;
            $dataArray['form'] = $mailingForm;
            $dataArray['header'] = \Yii::t('base', 'Current subscriptions');
            $dataArray['template'] = 'account-mailings-unsubscribe.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountMailingsFormWidget
     * @param array $mailingsArray
     * @param AbstractBaseForm $mailingForm
     * @return array
     */
    private function accountMailingsFormWidgetConfig(array $mailingsArray, AbstractBaseForm $mailingForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['mailings'] = $mailingsArray;
            $dataArray['form'] = $mailingForm;
            $dataArray['header'] = \Yii::t('base', 'Sign up now!');
            $dataArray['template'] = 'account-mailings-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
