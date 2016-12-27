<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\finders\{CategoriesFinder,
    CurrencySessionFinder,
    CurrencyFinder,
    FiltersSessionFinder,
    MainCurrencyFinder,
    PurchasesSessionFinder};
use app\helpers\HashHelper;
use app\forms\ChangeCurrencyForm;
use app\savers\SessionSaver;
use app\models\CurrencyModel;
use app\filters\ProductsFilters;

/**
 * Коллекция свойств и методов для рендеринга страниц пользовательского интерфейса
 */
trait FrontendTrait
{
    /**
     * @var CurrencyModel текущая валюта
     */
    private $currencyModel = null;
    /**
     * @var array данные для UserInfoWidget
     */
    private $userArray = [];
    /**
     * @var array данные для CartWidget
     */
    private $cartArray = [];
    /**
     * @var array данные для CurrencyWidget
     */
    private $currencyArray = [];
    /**
     * @var array данные для SearchWidget
     */
    private $searchArray = [];
    /**
     * @var array данные для CategoriesMenuWidget
     */
    private $categoriesArray = [];
    
    /**
     * Возвращает данные текущей валюты
     * Первый запрос отправляет в сессию, 
     * если данных нет, в СУБД и сохраняет полученные данные в сессию
     * @return CurrencyModel
     */
    private function getCurrencyModel(): CurrencyModel
    {
        try {
            if (empty($this->currencyModel)) {
                $key = HashHelper::createCurrencyKey();
                
                $finder = new CurrencySessionFinder([
                    'key'=>$key
                ]);
                $currencyModel = $finder->find();
                
                if (empty($currencyModel)) {
                    $finder = new MainCurrencyFinder();
                    $currencyModel = $finder->find();
                    if (empty($currencyModel)) {
                        throw new ErrorException($this->emptyError('currencyModel'));
                    }
                    
                    $saver = new SessionSaver([
                        'key'=>$key,
                        'models'=>[$currencyModel]
                    ]);
                    $saver->save();
                }
                
                $this->currencyModel = $currencyModel;
            }
            
            return $this->currencyModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UserInfoWidget
     * @return array
     */
    private function getUserArray(): array
    {
        try {
            if (empty($this->userArray)) {
                $dataArray = [];
                
                $dataArray['user'] = \Yii::$app->user;
                $dataArray['view'] = 'user-info.twig';
                
                $this->userArray = $dataArray;
            }
            
            return $this->userArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CartWidget
     * @return array
     */
    private function getCartArray(): array
    {
        try {
            if (empty($this->cartArray)) {
                $dataArray = [];
                
                $finder = new PurchasesSessionFinder([
                    'key'=>HashHelper::createCartKey()
                ]);
                $purchasesCollection = $finder->find();
                
                $dataArray['purchases'] = $purchasesCollection;
                $dataArray['currency'] = $this->getCurrencyModel();
                $dataArray['view'] = 'short-cart.twig';
                
                $this->cartArray = $dataArray;
            }
            
            return $this->cartArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CurrencyWidget
     * @return array
     */
    private function getCurrencyArray(): array
    {
        try {
            if (empty($this->currencyArray)) {
                $dataArray = [];
                
                $finder = new CurrencyFinder();
                $currencyArray = $finder->find();
                if (empty($currencyArray)) {
                    throw new ErrorException($this->emptyError('currencyArray'));
                }
                
                ArrayHelper::multisort($currencyArray, 'code');
                $dataArray['currency'] = ArrayHelper::map($currencyArray, 'id', 'code');
                $dataArray['form'] = new ChangeCurrencyForm(['url'=>Url::current(), 'id'=>$this->getCurrencyModel()->id]);
                $dataArray['view'] = 'currency-form.twig';
                
                $this->currencyArray = $dataArray;
            }
            
            return $this->currencyArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета SearchWidget
     * @return array
     */
    private function getSearchArray(): array
    {
        try {
            if (empty($this->searchArray)) {
                $dataArray = [];
                
                $dataArray['text'] = $request[\Yii::$app->params['searchKey']] ?? '';
                $dataArray['view'] = 'search.twig';
                
                $this->searchArray = $dataArray;
            }
            
            return $this->searchArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CategoriesMenuWidget
     * @return array
     */
    private function getCategoriesArray(): array
    {
        try {
            if (empty($this->categoriesArray)) {
                $dataArray = [];
                
                $finder = new CategoriesFinder();
                $categoriesArray = $finder->find();
                if (empty($categoriesArray)) {
                    throw new ErrorException($this->emptyError('categoriesArray'));
                }
                
                $dataArray['categories'] = $categoriesArray;
            
                $this->categoriesArray = $dataArray;
            }
            
            return $this->categoriesArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
