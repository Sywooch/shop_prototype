<?php

namespace app\handlers;

use yii\base\Object;
use yii\helpers\{ArrayHelper,
    Url};
use app\exceptions\ExceptionsTrait;
use app\handlers\HandlerInterface;
use app\models\CurrencyInterface;
use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;
use app\filters\ProductsFiltersInterface;
use app\finders\{CategoriesFinder,
    CurrencyFinder,
    ProductsFiltersSessionFinder,
    PurchasesSessionFinder};
use app\forms\ChangeCurrencyForm;
use app\collections\CollectionInterface;

/**
 * Базовый класс для обработчиков запроса
 */
abstract class AbstractBaseHandler extends Object implements HandlerInterface
{
    use ExceptionsTrait;
    
    /**
     * Возвращает CurrencyInterface объект текущей валюты
     * @return CurrencyInterface
     */
    /*protected function getCurrentCurrency(): CurrencyInterface
    {
        try {
            $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                'key'=>HashHelper::createCurrencyKey()
            ]);
            $currentCurrencyModel = $service->get();
            
            if (empty($currentCurrencyModel)) {
                throw new ErrorException($this->emptyError('currentCurrencyModel'));
            }
            
            return $currentCurrencyModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }*/
    
    /**
     * Возвращает ProductsFiltersInterface объект товарных фильтров
     * @return ProductsFiltersInterface
     */
    protected function getProductsFilters(): ProductsFiltersInterface
    {
        try {
            $finder = \Yii::$app->registry->get(ProductsFiltersSessionFinder::class, [
                'key'=>HashHelper::createFiltersKey(Url::current())
            ]);
            $filtersModel = $finder->find();
            
            if (empty($filtersModel)) {
                throw new ErrorException($this->emptyError('filtersModel'));
            }
            
            return $filtersModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UserInfoWidget
     * @return array
     */
    /*protected function userInfoWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['user'] = \Yii::$app->user;
            $dataArray['template'] = 'user-info.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }*/
    
    /**
     * Возвращает массив конфигурации для виджета ShortCartWidget
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    protected function shortCartWidgetConfig(CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                'key'=>HashHelper::createCartKey()
            ]);
            $ordersCollection = $finder->find();
            
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
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    protected function currencyWidgetConfig(CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(CurrencyFinder::class);
            $currencyArray = $finder->find();
            if (empty($currencyArray)) {
                throw new ErrorException($this->emptyError('currencyArray'));
            }
            ArrayHelper::multisort($currencyArray, 'code');
            $dataArray['currency'] = ArrayHelper::map($currencyArray, 'id', 'code');
            
            $dataArray['form'] = new ChangeCurrencyForm([
                'scenario'=>ChangeCurrencyForm::SET,
                'id'=>$currentCurrencyModel->id,
                'url'=>Url::current()
            ]);
            
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
    protected function searchWidgetConfig(string $searchKey=''): array
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
     * @return array
     */
    protected function categoriesMenuWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(CategoriesFinder::class);
            $categoriesArray = $finder->find();
            
            if (empty($categoriesArray)) {
                throw new ErrorException($this->emptyError('categoriesArray'));
            }
            
            $dataArray['categories'] = $categoriesArray;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета EmptyProductsWidget
     * @return array
     */
    protected function emptyProductsWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['template'] = 'empty-products.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ProductsWidget
     * @param CollectionInterface $productsCollection
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    protected function productsWidgetConfig(CollectionInterface $productsCollection, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['products'] = $productsCollection;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'products-list.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PaginationWidget
     * @param CollectionInterface $productsCollection
     * @return array
     */
    protected function paginationWidgetConfig(CollectionInterface $productsCollection): array
    {
        try {
            $dataArray = [];
            
            $pagination = $productsCollection->pagination;
            
            if (empty($pagination)) {
                throw new ErrorException($this->emptyError('pagination'));
            }
            
            $dataArray['pagination'] = $pagination;
            $dataArray['template'] = 'pagination.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
