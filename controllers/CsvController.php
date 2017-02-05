<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\AjaxAction;
use app\services\{CsvGetOrdersService,
    CsvGetProductsService};

/**
 * Обрабатывает запросы на обработку данных с применением csv
 */
class CsvController extends Controller
{
    public function actions()
    {
        return [
            'get-orders'=>[
                'class'=>AjaxAction::class,
                'service'=>new CsvGetOrdersService(),
            ],
            'get-products'=>[
                'class'=>AjaxAction::class,
                'service'=>new CsvGetProductsService(),
            ],
        ];
    }
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::class,
                'rules'=>[
                    [
                        'allow'=>true,
                        'roles'=>['superUser']
                    ],
                    [
                        'allow'=>false,
                        'roles'=>['?', '@']
                    ],
                ],
            ],
        ];
    }
}
