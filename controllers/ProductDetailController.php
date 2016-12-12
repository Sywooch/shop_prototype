<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use app\actions\SearchAction;
use app\services\ProductDetailIndexService;

/**
 * Обрабатывает запросы на получение информации о конкретном товаре
 */
class ProductDetailController extends AbstractBaseController
{
    public function actions()
    {
        return [
            'index'=>[
                'class'=>SearchAction::class,
                'service'=>new ProductDetailIndexService(),
                'view'=>'product-detail.twig',
            ],
        ];
    }
}
