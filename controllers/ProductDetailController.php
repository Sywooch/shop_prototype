<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use app\mappers\ProductDetailMapper;
use app\mappers\SimilarProductsMapper;

/**
 * Обрабатывает запросы на получение информации о конкретном продукте
 */
class ProductDetailController extends AbstractBaseController
{
    /**
     * @var object экземпляр ProductsModel, представляющий текущий продукт
     */
    private $_productsObject;
    
    /**
     * Обрабатывает запрос к конкретному продукту, рендерит ответ
     * @return string
     */
    public function actionIndex()
    {
        try {
            # Получаю массив объектов товаров
            $productMapper = new ProductDetailMapper([
                'tableName'=>'products',
                'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
            ]);
            $this->_productsObject = $productMapper->getOne();
            $dataForRender = $this->getDataForRender();
            $resultArray = array_merge(['productsObject'=>$this->_productsObject], $dataForRender);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $this->render('product-detail.twig', $resultArray);
    }
    
    /**
     * Получает данные, необходимые в нескольких типах контроллеров 
     * @return array
     */
    protected function getDataForRender()
    {
        try {
            $result = parent::getDataForRender();
            
            # Получаю массив similar products
            $similarProductsMapper = new SimilarProductsMapper([
                'tableName'=>'products',
                'fields'=>['id', 'name', 'price', 'images'],
                'otherTablesFields'=>[
                    ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                    ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
                ],
                'orderByField'=>'date',
                'model'=>$this->_productsObject,
            ]);
            $result['similarProductsList'] = $similarProductsMapper->getGroup();
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $result;
    }
}
