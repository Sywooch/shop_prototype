<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use app\actions\SearchAction;
use app\services\{ProductsListIndexService,
    ProductsListSearchService};

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends AbstractBaseController
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
