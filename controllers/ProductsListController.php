<?php

namespace app\controllers;

use yii\web\Controller;
use app\mappers\ProductsListMapper;

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends Controller
{
    /**
     * Обрабатывает запрос к списку продуктов, рендерит ответ
     * @return string
     */
    public function actionIndex()
    {
        $productsMapper = new ProductsListMapper([
            'tableName'=>'products',
            'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
            'orderByField'=>'price'
        ]);
        $productsList = $productsMapper->getGroup();
        return $this->render('content.twig', ['productsList'=>$productsList]);
    }
}
