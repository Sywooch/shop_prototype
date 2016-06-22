<?php

namespace app\controllers;

use app\controllers\AbstractBaseProductsController;
use app\mappers\ProductsListMapper;
use app\mappers\ColorsMapper;
use app\mappers\SizesMapper;
use app\mappers\BrandsMapper;
use app\models\FiltersModel;

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends AbstractBaseProductsController
{
    private $_config = [
        'tableName'=>'products',
        'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
        'otherTablesFields'=>[
            ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
            ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
        ],
        'orderByField'=>'date',
        'getDataSorting'=>false,
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
            print_r(\Yii::$app->params['productsFiltersArray']);
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
    
    /**
     * Получает данные, необходимые в нескольких типах контроллеров 
     * @return array
     */
    protected function getDataForRender()
    {
        try {
            $result = parent::getDataForRender();
            
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
            
            # Получаю массив brands для фильтра
            $brandsMapper = new BrandsMapper([
                'tableName'=>'brands',
                'fields'=>['id', 'brand'],
                'orderByField'=>'brand'
            ]);
            $result['brandsList'] = $brandsMapper->getGroup();
            
            $filtersModel = new FiltersModel(['scenario'=>FiltersModel::GET_FROM_FORM]);
            $filtersModel->attributes = \Yii::$app->params['productsFiltersArray'];
            $result['filtersModel'] = $filtersModel;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $result;
    }
}
