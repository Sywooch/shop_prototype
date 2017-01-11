<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\{AjaxAction,
    GetAction};
use app\services\{CartAddService,
    CartCleanService,
    CartIndexService};

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
            /*'update'=>[
                'class'=>AjaxAction::class,
                'service'=>new CartCleanService(),
            ],*/
        ];
    }
}
