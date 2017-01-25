<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\GetAction;
use app\services\{AdminIndexService,
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
