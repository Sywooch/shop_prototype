<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\{AjaxAction,
    GetAction};
use app\handlers\{AdminIndexRequestHandler,
    AdminOrderDetailChangeRequestHandler,
    AdminOrderDetailFormRequestHandler,
    AdminOrdersRequestHandler,
    AdminProductDetailFormRequestHandler,
    AdminProductsRequestHandler};

/**
 * Обрабатывает запросы к админ разделу
 */
class AdminController extends Controller
{
    public function actions()
    {
        return [
            'index'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminIndexRequestHandler(),
                'view'=>'index.twig',
            ],
            'orders'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminOrdersRequestHandler(),
                'view'=>'orders.twig',
            ],
            'order-detail-form'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminOrderDetailFormRequestHandler()
            ],
            'order-detail-change'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminOrderDetailChangeRequestHandler(),
            ],
            'products'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminProductsRequestHandler(),
                'view'=>'products.twig',
            ],
            'product-detail-form'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminProductDetailFormRequestHandler(),
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
                        'roles'=>['superUser']
                    ],
                    [
                        'allow'=>false,
                        'roles'=>['?', '@']
                    ],
                ],
            ],
        ];
    }
}
