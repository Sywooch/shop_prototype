<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\AjaxAction;
use app\handlers\{CsvGetOrdersRequestHandler,
    CsvGetProductsRequestHandler};

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
                'handler'=>new CsvGetOrdersRequestHandler(),
            ],
            'get-products'=>[
                'class'=>AjaxAction::class,
                'handler'=>new CsvGetProductsRequestHandler(),
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
