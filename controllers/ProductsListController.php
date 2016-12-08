<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\actions\SearchAction;
use app\services\{ProductsListIndexService,
    ProductsListSearchService};
use app\collections\{BaseCollection,
    BaseSessionCollection};
use app\finders\{OneSessionFinder,
    MainCurrencyFinder};

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
                'view'=>'products-list.twig'
            ],
        ];
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\ProductsFilter',
            ],
            [
                'class'=>'app\filters\CurrencyFilter',
                'sessionFinder'=>new OneSessionFinder([
                    'collection'=>new BaseSessionCollection()
                ]),
                'finder'=>new MainCurrencyFinder([
                    'collection'=>new BaseCollection()
                ])
            ],
            [
                'class'=>'app\filters\CartFilter',
            ],
        ];
    }
}
