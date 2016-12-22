<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\SearchAction;
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
                'class'=>SearchAction::class,
                'service'=>new ProductsListIndexService(),
                'view'=>'products-list.twig'
            ],
            'search'=>[
                'class'=>SearchAction::class,
                'service'=>new ProductsListSearchService(),
                'view'=>'products-search.twig'
            ],
        ];
    }
}
