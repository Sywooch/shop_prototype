<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use app\mappers\ProductsListMapper;

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends AbstractBaseController
{
    private $_config = [
        'tableName'=>'products',
        'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
        'otherTablesFields'=>[
            ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
            ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
        ],
        'orderByField'=>'date'
    ];
    
    /**
     * Обрабатывает запрос к списку продуктов, рендерит ответ
     * @return string
     */
    public function actionIndex()
    {
        try {
            # Получаю массив объектов товаров
            $productsMapper = new ProductsListMapper($this->_config);
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
     * Обрабатывает поисковый запрос к списку продуктов, рендерит ответ
     * @return string
     */
    public function actionSearch()
    {
        try {
            $this->_config['queryClass'] = 'app\queries\ProductsListSearchQueryCreator';
            
            # Получаю массив объектов товаров
            $productsMapper = new ProductsListMapper($this->_config);
            $productsList = $productsMapper->getGroup();
            $dataForRender = $this->getDataForRender();
            $resultArray = array_merge(['productsList'=>$productsList], $dataForRender);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $this->render('products-list.twig', $resultArray);
    }
}
