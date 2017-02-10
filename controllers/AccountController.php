<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\{AjaxAction,
    GetAction};
use app\services\{AccountChangeDataPostService,
    AccountChangePasswordPostService,
    AccountChangePasswordService,
    AccountChangeSubscriptionsService,
    AccountSubscriptionsAddService,
    AccountSubscriptionsCancelService};
use app\handlers\{AccountChangeDataRequestHandler,
    AccountIndexRequestHandler,
    AccountOrdersCancelRequestHandler,
    AccountOrdersRequestHandler};

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
                'handler'=>new AccountIndexRequestHandler(),
                'view'=>'index.twig',
            ],
            'orders'=>[
                'class'=>GetAction::class,
                'handler'=>new AccountOrdersRequestHandler(),
                'view'=>'orders.twig',
            ],
            'order-cancel'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AccountOrdersCancelRequestHandler(),
            ],
            'data'=>[
                'class'=>GetAction::class,
                'handler'=>new AccountChangeDataRequestHandler(),
                'view'=>'change-data.twig',
            ],
            /*'change-data-post'=>[
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
            ],*/
        ];
    }
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::class,
                'rules'=>[
                    [
                        'allow'=>false,
                        'roles'=>['?']
                    ],
                    [
                        'allow'=>false,
                        'actions'=>['order-cancel', 'change-data-post', 'change-password-post', 'subscriptions-cancel', 'subscriptions-add'],
                        'verbs'=>['GET'],
                        'roles'=>['@'],
                    ],
                    [
                        'allow'=>true,
                        'roles'=>['@']
                    ],
                ],
            ],
        ];
    }
}
