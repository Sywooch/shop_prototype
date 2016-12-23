<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\services\AbstractBaseService;
use app\finders\{CategoriesFinder,
    CurrencySessionFinder,
    CurrencyFinder,
    MainCurrencyFinder,
    PurchasesSessionFinder};
use app\helpers\HashHelper;
use app\forms\ChangeCurrencyForm;
use app\savers\SessionSaver;
use app\models\CurrencyModel;

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
     * @var array текущий пользователь
     */
    private $userArray = [];
    /**
     * @var array текущее состояние корзины
     */
    private $cartArray = [];
    /**
     * @var array доступные валюты
     */
    private $currencyArray = [];
    /**
     * @var array строка поиска
     */
    private $searchArray = [];
    /**
     * @var array меню категорий
     */
    private $categoriesArray = [];
    
    /**
     * Возвращает данные текущей валюты
     * @return CurrencyModel
     */
    protected function currentCurrency(): CurrencyModel
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
     * Возвращает данные для вывода информации о текущем пользователе
     * @return array
     */
    protected function user(): array
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
     * Возвращает данные для вывода информации о состоянии корзины
     * @return array
     */
    protected function cart(): array
    {
        try {
            if (empty($this->cartArray)) {
                $dataArray = [];
                
                $finder = new PurchasesSessionFinder([
                    'key'=>HashHelper::createCartKey()
                ]);
                $purchasesCollection = $finder->find();
                
                $dataArray['purchases'] = $purchasesCollection;
                $dataArray['currency'] = $this->currentCurrency();
                $dataArray['view'] = 'short-cart.twig';
                
                $this->cartArray = $dataArray;
            }
            
            return $this->cartArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные для вывода списка доступных валют
     * @return array
     */
    protected function currency(): array
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
                $dataArray['form'] = new ChangeCurrencyForm(['url'=>Url::current(), 'id'=>$this->currentCurrency()->id]);
                $dataArray['view'] = 'currency-form.twig';
                
                $this->currencyArray = $dataArray;
            }
            
            return $this->currencyArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные для вывода строки поиска
     * @return array
     */
    protected function search(): array
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
     * Возвращает данные для вывода меню категорий
     * @return array
     */
    protected function categories(): array
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
