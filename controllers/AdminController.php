<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\{AjaxAction,
    GetAction,
    RedirectAction};
use app\services\{AdminIndexService,
    AdminOrdersChangeStatusService,
    AdminOrderDetailService,
    AdminOrdersService};

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
            'order-detail'=>[
                'class'=>GetAction::class,
                'service'=>new AdminOrderDetailService(),
                'view'=>'order-detail.twig',
            ],
            /*'orders-change-status'=>[
                'class'=>RedirectAction::class,
                'service'=>new AdminOrdersChangeStatusService(),
            ]*/
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
