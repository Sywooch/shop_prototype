<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\AjaxAction;
use app\handlers\CalendarGetRequestHandler;

/**
 * Обрабатывает запросы, связанные с календарем
 */
class CalendarController extends Controller
{
    public function actions()
    {
        return [
            'get'=>[
                'class'=>AjaxAction::class,
                'handler'=>new CalendarGetRequestHandler()
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
                        'allow'=>false,
                        'verbs'=>['GET']
                    ],
                    [
                        'allow'=>true,
                        'roles'=>['?', '@']
                    ],
                ],
            ],
        ];
    }
}
