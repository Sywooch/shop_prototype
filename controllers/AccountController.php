<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\{AjaxAction,
    GetAction};
use app\handlers\{AccountChangeDataPostRequestHandler,
    AccountChangeDataRequestHandler,
    AccountChangePasswordPostRequestHandler,
    AccountChangePasswordRequestHandler,
    AccountChangeSubscriptionsRequestHandler,
    AccountIndexRequestHandler,
    AccountOrdersCancelRequestHandler,
    AccountOrdersRequestHandler,
    AccountSubscriptionsAddRequestHandler,
    AccountSubscriptionsCancelRequestHandler};

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
            'change-data-post'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AccountChangeDataPostRequestHandler(),
            ],
            'password'=>[
                'class'=>GetAction::class,
                'handler'=>new AccountChangePasswordRequestHandler(),
                'view'=>'change-password.twig',
            ],
            'change-password-post'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AccountChangePasswordPostRequestHandler(),
            ],
            'subscriptions'=>[
                'class'=>GetAction::class,
                'handler'=>new AccountChangeSubscriptionsRequestHandler(),
                'view'=>'change-subscriptions.twig',
            ],
            'subscriptions-cancel'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AccountSubscriptionsCancelRequestHandler(),
            ],
            'subscriptions-add'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AccountSubscriptionsAddRequestHandler(),
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
