<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\{AjaxAction,
    GetAction};
use app\services\{AccountChangeDataPostService,
    AccountChangeDataService,
    AccountChangePasswordPostService,
    AccountChangePasswordService,
    AccountChangeSubscriptionsService,
    AccountIndexService,
    AccountOrdersCancelService,
    AccountOrdersService,
    AccountSubscriptionsAddService,
    AccountSubscriptionsCancelService};

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
            'data'=>[
                'class'=>GetAction::class,
                'service'=>new AccountChangeDataService(),
                'view'=>'change-data.twig',
            ],
            'change-data-post'=>[
                'class'=>AjaxAction::class,
                'service'=>new AccountChangeDataPostService(),
            ],
            'password'=>[
                'class'=>GetAction::class,
                'service'=>new AccountChangePasswordService(),
                'view'=>'change-password.twig',
            ],
            'change-password-post'=>[
                'class'=>AjaxAction::class,
                'service'=>new AccountChangePasswordPostService(),
            ],
            'subscriptions'=>[
                'class'=>GetAction::class,
                'service'=>new AccountChangeSubscriptionsService(),
                'view'=>'change-subscriptions.twig',
            ],
            'subscriptions-cancel'=>[
                'class'=>AjaxAction::class,
                'service'=>new AccountSubscriptionsCancelService(),
            ],
            'subscriptions-add'=>[
                'class'=>AjaxAction::class,
                'service'=>new AccountSubscriptionsAddService(),
            ],
        ];
    }
}
