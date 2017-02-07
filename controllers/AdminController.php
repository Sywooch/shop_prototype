<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\{AjaxAction,
    GetAction};
use app\services\{AdminIndexService,
    AdminOrderDetailChangeService,
    AdminOrderDetailFormService,
    AdminOrdersService,
    AdminProductsService};
use app\handlers\AdminProductDetailFormHandler;

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
                'service'=>new AdminIndexService(),
                'view'=>'index.twig',
            ],
            'orders'=>[
                'class'=>GetAction::class,
                'service'=>new AdminOrdersService(),
                'view'=>'orders.twig',
            ],
            'order-detail-form'=>[
                'class'=>AjaxAction::class,
                'service'=>new AdminOrderDetailFormService(),
            ],
            'order-detail-change'=>[
                'class'=>AjaxAction::class,
                'service'=>new AdminOrderDetailChangeService(),
            ],
            'products'=>[
                'class'=>GetAction::class,
                'service'=>new AdminProductsService(),
                'view'=>'products.twig',
            ],
            'product-detail-form'=>[
                'class'=>AjaxAction::class,
                'service'=>new AdminProductDetailFormHandler(),
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
