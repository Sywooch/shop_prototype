<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\controllers\AbstractBaseController;
use app\helpers\UrlHelper;
use app\actions\DetailAction;
use app\models\{CurrencyFilter,
    ProductsFilter,
    PurchasesFilter,
    PurchasesModel,
    UsersFilter};

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
                'filterClass'=>new ProductsFilter(),
                'filterScenario'=>'detailSearch',
                'view'=>'product-detail.twig',
                'additions'=>[
                    'purchase'=>[
                        'class'=>PurchasesModel::class,
                        'quantity'=>1,
                    ],
                    'currency'=>[
                        'filterClass'=>new CurrencyFilter(),
                        'filterScenario'=>'widgetSearch',
                        'view'=>'currency-form.twig',
                    ],
                    'cart'=>[
                        'filterClass'=>new PurchasesFilter(),
                        'filterScenario'=>'sessionSearch',
                        'view'=>'short-cart.twig',
                    ],
                    'user'=>[
                        'filterClass'=>new UsersFilter(),
                        'filterScenario'=>'sessionSearch',
                        'view'=>'user-info.twig',
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
        ];
    }
}
