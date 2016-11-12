<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\controllers\AbstractBaseController;
use app\helpers\UrlHelper;
use app\actions\DetailAction;
use app\models\{CurrencyFinder,
    ProductsFinder,
    PurchasesModel};

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
                'finderClass'=>new ProductsFinder(),
                'finderScenario'=>'detail',
                'view'=>'product-detail.twig',
                'additions'=>[
                    'purchase'=>[
                        'class'=>PurchasesModel::class,
                        'quantity'=>1,
                    ],
                    'currency'=>[
                        'finderClass'=>new CurrencyFinder(),
                        'finderScenario'=>'widget',
                        'view'=>'currency-form.twig',
                    ],
                    'cart'=>[
                        'view'=>'short-cart.twig',
                    ],
                ],
            ],
        ];
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\CurrencyFilter',
            ],
            [
                'class'=>'app\filters\CartFilter',
            ],
        ];
    }
}
