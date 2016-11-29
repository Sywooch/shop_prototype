<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\actions\SearchAction;
use app\models\{Collection,
    ProductsCollection,
    ProductsModel,
    SphinxModel};
use app\repositories\DbRepository;
use app\services\{ProductsListIndexService,
    SphinxSearchService};
use app\queries\{LightPagination,
    QueryCriteria};
use app\search\ProductsSearchModel;

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
            /*'search'=>[
                'class'=>SearchAction::class,
                'service'=>new SphinxSearchService([
                    'sphinxRepository'=>new DbRepository([
                        'query'=>SphinxModel::find(),
                        'collection'=>new Collection(),
                        //'criteria'=>new QueryCriteria()
                    ]),
                    'productsRepository'=>new DbRepository([
                        'query'=>ProductsModel::find(),
                        'collection'=>new ProductsCollection([
                            'pagination'=>new LightPagination()
                        ]),
                        //'criteria'=>new QueryCriteria()
                    ]),
                ]),
                'view'=>'products-search.twig'
            ],*/
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
            ],
            [
                'class'=>'app\filters\CartFilter',
            ],
        ];
    }
}
