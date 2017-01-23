<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
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
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::class,
                'rules'=>[
                    [
                        'allow'=>true,
                        'roles'=>['?', '@']
                    ],
                ],
            ],
        ];
    }
}
