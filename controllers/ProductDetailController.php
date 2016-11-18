<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\actions\DetailAction;
use app\services\OneProductSearchService;
use app\repository\ProductsRepository;

/**
 * Обрабатывает запросы на получение информации о конкретном продукте
 */
class ProductDetailController extends AbstractBaseController
{
    public function actions()
    {
        return [
            'index'=>[
                'class'=>DetailAction::class,
                'service'=>new OneProductSearchService(['repository'=>new ProductsRepository()]),
                'view'=>'product-detail.twig',
            ],
        ];
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\CurrencyFilter',
            ],
        ];
    }
}
