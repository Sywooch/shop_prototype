<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\{AjaxAction,
    GetAction};
use app\services\{AccountIndexService,
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
            /*'order-cancel'=>[
                'class'=>AjaxAction::class,
                'service'=>new AccountOrdersCancelService(),
            ],*/
        ];
    }
}
