<?php

namespace app\controllers;

use yii\web\Controller;
use app\mappers\ProductsListMapper;
use app\mappers\CategoriesMapper;
use app\mappers\CurrencyMapper;
use app\mappers\ColorsMapper;
use app\mappers\SizesMapper;
use app\traits\ExceptionsTrait;

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends Controller
{
    use ExceptionsTrait;
    
    /**
     * Обрабатывает запрос к списку продуктов, рендерит ответ
     * @return string
     */
    public function actionIndex()
    {
        try {
            # Получаю массив объектов товаров
            $productsMapper = new ProductsListMapper([
                'tableName'=>'products',
                'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
                'otherTablesFields'=>[
                    ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                    ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
                ],
                'orderByField'=>'date'
            ]);
            $productsList = $productsMapper->getGroup();
            echo $productsMapper->query;
            $dataForRender = $this->getDataForRender();
            $resultArray = array_merge(['productsList'=>$productsList], $dataForRender);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $this->render('products-list.twig', $resultArray);
    }
    
    /**
     * Обрабатывает поисковый запрос к списку продуктов, рендерит ответ
     * @return string
     */
    public function actionSearch()
    {
        try {
            # Получаю массив объектов товаров
            $productsMapper = new ProductsListMapper([
                'tableName'=>'products',
                'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
                'otherTablesFields'=>[
                    ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                    ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
                ],
                'orderByField'=>'date',
                'queryClass'=>'app\queries\ProductsListSearchQueryCreator',
            ]);
            $productsList = $productsMapper->getGroup();
            $dataForRender = $this->getDataForRender();
            $resultArray = array_merge(['productsList'=>$productsList], $dataForRender);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $this->render('products-list.twig', $resultArray);
    }
    
    /**
     * Получает данные, присутствующие во всех выводах списка продуктов
     * @return array
     */
    private function getDataForRender()
    {
        try {
            $result = array();
            
            # Получаю массив объектов категорий
            $categoriesMapper = new CategoriesMapper([
                'tableName'=>'categories',
                'fields'=>['id', 'name', 'seocode'],
                'orderByField'=>'name'
            ]);
            $result['categoriesList'] = $categoriesMapper->getGroup();
            
            # Получаю массив объектов валют
            $currencyMapper = new CurrencyMapper([
                'tableName'=>'currency',
                'fields'=>['id', 'currency'],
                'orderByField'=>'currency'
            ]);
            $result['currencyList'] = $currencyMapper->getGroup();
            
            # Получаю массив объектов цветов для фильтра
            $colorsMapper = new ColorsMapper([
                'tableName'=>'colors',
                'fields'=>['id', 'color'],
                'orderByField'=>'color',
            ]);
            $result['colorsList'] = $colorsMapper->getGroup();
            
            # Получаю массив объектов размеров для фильтра
            $sizesMapper = new SizesMapper([
                'tableName'=>'sizes',
                'fields'=>['id', 'size'],
                'orderByField'=>'size'
            ]);
            $result['sizesList'] = $sizesMapper->getGroup();
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $result;
    }
}
