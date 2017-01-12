<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\{AjaxAction,
    GetAction,
    RedirectAction};
use app\services\{CartAddService,
    CartCleanRedirectService,
    CartCleanService,
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
            'index'=>[
                'class'=>GetAction::class,
                'service'=>new CartIndexService(),
                'view'=>'cart.twig'
            ],
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
        ];
    }
}
