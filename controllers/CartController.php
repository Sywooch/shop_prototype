<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\{AjaxAction,
    GetAction,
    RedirectAction};
use app\handlers\{CartAddRequestHandler,
    CartCheckoutAjaxFormRequestHandler,
    CartCleanRequestHandler,
    CartCleanRedirectRequestHandler,
    CartDeleteRequestHandler,
    CartIndexRequestHandler,
    CartUpdateRequestHandler};

/**
 * Обрабатывает запросы, касающиеся комментариев к товарам
 */
class CartController extends Controller
{
    public function actions()
    {
        return [
            'add'=>[
                'class'=>AjaxAction::class,
                'handler'=>new CartAddRequestHandler(),
            ],
            'clean'=>[
                'class'=>AjaxAction::class,
                'handler'=>new CartCleanRequestHandler(),
            ],
            'clean-redirect'=>[
                'class'=>RedirectAction::class,
                'handler'=>new CartCleanRedirectRequestHandler(),
            ],
            'update'=>[
                'class'=>AjaxAction::class,
                'handler'=>new CartUpdateRequestHandler(),
            ],
            'delete'=>[
                'class'=>AjaxAction::class,
                'handler'=>new CartDeleteRequestHandler(),
            ],
            'index'=>[
                'class'=>GetAction::class,
                'handler'=>new CartIndexRequestHandler(),
                'view'=>'cart.twig'
            ],
            'сheckout-ajax-form'=>[
                'class'=>AjaxAction::class,
                'handler'=>new CartCheckoutAjaxFormRequestHandler(),
            ],
            'сheckout-ajax'=>[
                'class'=>AjaxAction::class,
                'handler'=>new CartCheckoutAjaxRequestHandler(),
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
                        'actions'=>['add', 'clean', 'clean-redirect', 'update', 'delete', 'сheckout-ajax-form', 'сheckout-ajax'],
                        'verbs'=>['GET'],
                    ],
                    [
                        'allow'=>true,
                        'roles'=>['?', '@'],
                    ],
                ],
            ],
        ];
    }
}
