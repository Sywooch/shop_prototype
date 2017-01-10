<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\AjaxAction;
use app\services\{PurchaseCleanService,
    PurchaseSaveService};

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
                'service'=>new PurchaseSaveService(),
            ],
            'clean'=>[
                'class'=>AjaxAction::class,
                'service'=>new PurchaseCleanService(),
            ],
        ];
    }
}
