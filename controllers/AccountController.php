<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\{AjaxAction,
    GetAction};
use app\services\{AccountChangeDataService,
    AccountIndexService,
    AccountOrdersCancelService,
    AccountOrdersService};

/**
 * Обрабатывает запросы к настройкам аккаунта
 */
class AccountController extends Controller
{
    public function actions()
    {
        return [
            'index'=>[
                'class'=>GetAction::class,
                'service'=>new AccountIndexService(),
                'view'=>'index.twig',
            ],
            'orders'=>[
                'class'=>GetAction::class,
                'service'=>new AccountOrdersService(),
                'view'=>'orders.twig',
            ],
            'order-cancel'=>[
                'class'=>AjaxAction::class,
                'service'=>new AccountOrdersCancelService(),
            ],
            'change-data'=>[
                'class'=>GetAction::class,
                'service'=>new AccountChangeDataService(),
                'view'=>'change-data.twig',
            ],
            /*'change-data-post'=>[
                'class'=>AjaxAction::class,
                'service'=>new AccountChangeDataPostService(),
            ],*/
        ];
    }
}
