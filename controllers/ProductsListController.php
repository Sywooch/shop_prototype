<?php

namespace app\controllers;

use yii\web\Controller;
use app\mappers\ProductsListMapper;

/**
 * Контроллер обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends Controller
{
    /**
     * 
     */
    public function actionIndex()
    {
        $productsMapper = new ProductsListMapper([
            'tableName'=>'products',
            'fields'=>['id', 'name', 'description', 'price'],
            'orderByField'=>'price'
        ]);
        $productsList = $productsMapper->getGroup();
        return $this->render('content.twig', ['productsList'=>$productsList]);
    }
}
