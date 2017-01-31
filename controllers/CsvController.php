<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\AjaxAction;
use app\services\CsvGetOrdersService;

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
