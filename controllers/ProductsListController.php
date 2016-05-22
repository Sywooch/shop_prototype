<?php

namespace app\controllers;

use yii\web\Controller;
use app\mappers\ProductListMapper;

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
        $productList = new ProductListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'price'], 'orderByField'=>'price']);
        print_r($productList->getGroup());
        return '';
    }
}
