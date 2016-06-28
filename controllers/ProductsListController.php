<?php

namespace app\controllers;

use app\controllers\AbstractBaseProductsController;
use yii\base\ErrorException;
use app\mappers\ProductsListMapper;
use app\mappers\ColorsMapper;
use app\mappers\SizesMapper;
use app\mappers\BrandsMapper;

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
            $productsMapper = new ProductsListMapper($this->_config);
            $productsArray = $productsMapper->getGroup();
            if (!is_array($dataForRender = $this->getDataForRender())) {
                throw new ErrorException('Ошибка при формировании массива данных!');
            }
            $resultArray = array_merge(['productsList'=>$productsArray], $dataForRender);
            return $this->render('products-list.twig', $resultArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает поисковый запрос к списку продуктов, рендерит ответ
     * @return string
     */
    public function actionSearch()
    {
        try {
            $this->_config['queryClass'] = 'app\queries\ProductsListSearchQueryCreator';
            $productsMapper = new ProductsListMapper($this->_config);
            $productsArray = $productsMapper->getGroup();
            if (!is_array($dataForRender = $this->getDataForRender())) {
                throw new ErrorException('Ошибка при формировании массива данных!');
            }
            $resultArray = array_merge(['productsList'=>$productsArray], $dataForRender);
            return $this->render('products-list.twig', $resultArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Получает данные, необходимые в нескольких типах контроллеров 
     * @return array
     */
    protected function getDataForRender()
    {
        try {
            if (!is_array($result = parent::getDataForRender())) {
                throw new ErrorException('Ошибка при формировании массива данных!');
            }
            
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
            
            if (!isset(\Yii::$app->filters)) {
                throw new ErrorException('Не определен объект фильтров!');
            }
            $result['filtersModel'] = \Yii::$app->filters;
            return $result;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            ['class'=>'app\filters\ProductsListFilter'],
        ];
    }
}
