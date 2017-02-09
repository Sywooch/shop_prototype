<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\GetAction;
use app\handlers\{ProductsListIndexRequestHandler,
    ProductsListSearchRequestHandler};
use app\filters\VisitorsCounterFilter;

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
                'handler'=>new ProductsListIndexRequestHandler(),
                'view'=>'products-list.twig'
            ],
            'search'=>[
                'class'=>GetAction::class,
                'handler'=>new ProductsListSearchRequestHandler(),
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
            'visitsCounter'=>[
                'class'=>VisitorsCounterFilter::class,
            ],
        ];
    }
}
