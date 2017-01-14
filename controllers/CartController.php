<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\{AjaxAction,
    GetAction,
    RedirectAction};
use app\services\{CartAddService,
    CartCheckoutAjaxFormService,
    CartCheckoutAjaxService,
    CartCheckoutPostService,
    CartCheckoutService,
    CartCleanRedirectService,
    CartCleanService,
    CartConfirmService,
    CartDeleteService,
    CartIndexService,
    CartUpdateService};

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
                'service'=>new CartAddService(),
            ],
            'clean'=>[
                'class'=>AjaxAction::class,
                'service'=>new CartCleanService(),
            ],
            'clean-redirect'=>[
                'class'=>RedirectAction::class,
                'service'=>new CartCleanRedirectService(),
            ],
            'update'=>[
                'class'=>AjaxAction::class,
                'service'=>new CartUpdateService(),
            ],
            'delete'=>[
                'class'=>AjaxAction::class,
                'service'=>new CartDeleteService(),
            ],
            'index'=>[
                'class'=>GetAction::class,
                'service'=>new CartIndexService(),
                'view'=>'cart.twig'
            ],
            'сheckout-ajax-form'=>[
                'class'=>AjaxAction::class,
                'service'=>new CartCheckoutAjaxFormService(),
            ],
            'сheckout-ajax'=>[
                'class'=>AjaxAction::class,
                'service'=>new CartCheckoutAjaxService(),
            ],
            # !!!
            /*'сheckout'=>[
                'class'=>GetAction::class,
                'service'=>new CartCheckoutService(),
                'view'=>'checkout.twig'
            ],*/
            /*'сheckout-post'=>[
                'class'=>RedirectAction::class,
                'service'=>new CartCheckoutPostService(),
            ],*/
            # !!!
            /*'confirm'=>[
                'class'=>GetAction::class,
                'service'=>new CartConfirmService(),
                'view'=>'confirm.twig'
            ],*/
        ];
    }
}
