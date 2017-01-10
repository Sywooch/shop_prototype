<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\GetAction;
use app\services\ProductDetailIndexService;

/**
 * Обрабатывает запросы на получение информации о конкретном товаре
 */
class ProductDetailController extends Controller
{
    public function actions()
    {
        return [
            'index'=>[
                'class'=>GetAction::class,
                'service'=>new ProductDetailIndexService(),
                'view'=>'product-detail.twig',
            ],
        ];
    }
}
