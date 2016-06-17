<?php

namespace app\controllers;

use app\controllers\AbstractBaseProductsController;
use app\mappers\ProductDetailMapper;
use app\models\CommentsModel;
use app\models\ProductsModel;

/**
 * Обрабатывает запросы на получение информации о конкретном продукте
 */
class ProductDetailController extends AbstractBaseProductsController
{
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
            $productsObject = $productMapper->getOneFromGroup();
            $dataForRender = $this->getDataForRender();
            $resultArray = array_merge(['productsObject'=>$productsObject], $dataForRender);
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
            $result['commentsModel'] = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
            $result['productsModel'] = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $result;
    }
}
