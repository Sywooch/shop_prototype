<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\GetAction;
use app\services\{ProductsListIndexService,
    ProductsListSearchService};

/**
 * Обрабатывает запросы на получение списка продуктов 
 */
class ProductsListController extends Controller
{
    public function actions()
    {
        return [
            'index'=>[
                'class'=>GetAction::class,
                'service'=>new ProductsListIndexService(),
                'view'=>'products-list.twig'
            ],
            'search'=>[
                'class'=>GetAction::class,
                'service'=>new ProductsListSearchService(),
                'view'=>'products-search.twig'
            ],
        ];
    }
}
