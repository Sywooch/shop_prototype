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
        $productList = new ProductListMapper(['fields'=>['name', 'price', 'color'], 'orderByField'=>'date']);
        return $productList->getGroup();
    }
}
