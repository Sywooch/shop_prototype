<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use app\actions\SearchAction;
use app\services\{CommonFrontendService,
    ProductsListIndexService,
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
                'service'=>new ProductsListIndexService([
                    'commonService'=>new CommonFrontendService()
                ]),
                'view'=>'products-list.twig'
            ],
            'search'=>[
                'class'=>SearchAction::class,
                'service'=>new ProductsListSearchService([
                    'commonService'=>new CommonFrontendService()
                ]),
                'view'=>'products-search.twig'
            ],
        ];
    }
}
