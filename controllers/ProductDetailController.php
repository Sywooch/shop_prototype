<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\helpers\UrlHelper;
use app\actions\DetailAction;
use app\models\{ProductsModel,
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
                'modelClass'=>ProductsModel::class,
                'column'=>'seocode',
                'view'=>'product-detail.twig',
                'resultName'=>'product',
                'additions'=>[
                    'purchase'=>[
                        'class'=>PurchasesModel::class,
                        'quantity'=>1,
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
            [
                'class'=>'app\filters\GetEmptyFilter',
                'parameter'=>\Yii::$app->params['productKey'],
                'redirect'=>UrlHelper::previous(\Yii::$app->id)
            ],
            [
                'class'=>'app\filters\UrlRememberFilter',
                'name'=>\Yii::$app->id
            ],
        ];
    }
}
