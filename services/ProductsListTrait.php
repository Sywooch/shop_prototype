<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\Request;
use app\finders\FiltersSessionFinder;
use app\helpers\HashHelper;
use app\filters\ProductsFilters;
use app\services\GetCurrentCurrencyService;

/**
 * Коллекция свойств и методов для рендеринга страниц какталога товаров
 */
trait ProductsListTrait
{
    /**
     * @var ProductsFilters объект текущих фильтров
     */
    private $filtersModel = null;
    /**
     * @var array данные для EmptyProductsWidget
     */
    private $emptyProductsArray = [];
    /**
     * @var array данные для ProductsWidget
     */
    private $productsArray = [];
    /**
     * @var array данные для PaginationWidget
     */
    private $paginationArray = [];
    
    /**
     * Возвращает модель товарных фильтров
     * @return ProductsFilters
     */
    private function getFiltersModel(): ProductsFilters
    {
        try {
            if (empty($this->filtersModel)) {
                $finder = new FiltersSessionFinder([
                    'key'=>HashHelper::createFiltersKey(Url::current())
                 ]);
                $filtersModel = $finder->find();
                
                if (empty($filtersModel)) {
                    throw new ErrorException($this->emptyError('filtersModel'));
                }
                
                $this->filtersModel = $filtersModel;
            }
            
            return $this->filtersModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета EmptyProductsWidget
     * @return array
     */
    private function getEmptyProductsArray(): array
    {
        try {
            if (empty($this->emptyProductsArray)) {
                $dataArray = [];
                
                $dataArray['view'] = 'empty-products.twig';
                
                $this->emptyProductsArray = $dataArray;
            }
            
            return $this->emptyProductsArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ProductsWidget
     * @param Request $request данные запроса
     * @return array
     */
    private function getProductsArray(Request $request): array
    {
        try {
            if (empty($this->productsArray)) {
                $dataArray = [];
                
                $dataArray['products'] = $this->getProductsCollection($request);
                
                $service = new GetCurrentCurrencyService();
                $dataArray['currency'] = $service->handle();
                
                $dataArray['view'] = 'products-list.twig';
                
                $this->productsArray = $dataArray;
            }
            
            return $this->productsArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PaginationWidget
     * @param Request $request данные запроса
     * @return array
     */
    private function getPaginationArray(Request $request): array
    {
        try {
            if (empty($this->paginationArray)) {
                $dataArray = [];
                
                $pagination = $this->getProductsCollection($request)->pagination;
                
                if (empty($pagination)) {
                    throw new ErrorException($this->emptyError('pagination'));
                }
                
                $dataArray['pagination'] = $pagination;
                $dataArray['view'] = 'pagination.twig';
                
                $this->paginationArray = $dataArray;
            }
            
            return $this->paginationArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
