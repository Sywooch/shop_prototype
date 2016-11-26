<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\actions\SearchCollectionAction;
use app\models\{Collection,
    ProductsCollection,
    ProductsModel,
    SphinxModel};
use app\repositories\DbRepository;
use app\services\{ProductsCollectionSearchService,
    SphinxSearchService};
use app\queries\{LightPagination,
    QueryCriteria};

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends AbstractBaseController
{
    public function actions()
    {
        return [
            'index'=>[
                'class'=>SearchCollectionAction::class,
                'service'=>new ProductsCollectionSearchService([
                    'repository'=>new DbRepository([
                        'query'=>ProductsModel::find(),
                        'collection'=>new ProductsCollection([
                            'pagination'=>new LightPagination()
                        ]),
                        'criteria'=>new QueryCriteria()
                    ]),
                ]),
                'view'=>'products-list.twig'
            ],
            'search'=>[
                'class'=>SearchCollectionAction::class,
                'service'=>new SphinxSearchService([
                    'sphinxRepository'=>new DbRepository([
                        'query'=>SphinxModel::find(),
                        'collection'=>new Collection(),
                        'criteria'=>new QueryCriteria()
                    ]),
                    'productsRepository'=>new DbRepository([
                        'query'=>ProductsModel::find(),
                        'collection'=>new ProductsCollection([
                            'pagination'=>new LightPagination()
                        ]),
                        'criteria'=>new QueryCriteria()
                    ]),
                ]),
                'view'=>'products-search.twig'
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
            ],
            [
                'class'=>'app\filters\CartFilter',
            ],
        ];
    }
}
