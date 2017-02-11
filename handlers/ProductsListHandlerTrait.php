<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\filters\ProductsFiltersInterface;
use app\finders\ProductsFiltersSessionFinder;
use app\helpers\HashHelper;
use app\collections\CollectionInterface;
use app\models\CurrencyInterface;

/**
 * Коллекция базовых методов
 */
trait ProductsListHandlerTrait
{
    /**
     * Возвращает ProductsFiltersInterface объект товарных фильтров
     * @return ProductsFiltersInterface
     */
    private function getProductsFilters(): ProductsFiltersInterface
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
     * Возвращает массив конфигурации для виджета EmptyProductsWidget
     * @return array
     */
    private function emptyProductsWidgetConfig(): array
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
    private function productsWidgetConfig(CollectionInterface $productsCollection, CurrencyInterface $currentCurrencyModel): array
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
    private function paginationWidgetConfig(CollectionInterface $productsCollection): array
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
