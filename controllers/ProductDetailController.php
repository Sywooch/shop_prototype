<?php

namespace app\controllers;

use app\controllers\AbstractBaseProductsController;
use app\mappers\ProductDetailMapper;

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
            $productsObject = $productMapper->getOne();
            $dataForRender = $this->getDataForRender();
            $resultArray = array_merge(['productsObject'=>$productsObject], $dataForRender);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $this->render('product-detail.twig', $resultArray);
    }
}
