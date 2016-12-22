<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\PostRedirectAction;
use app\services\CurrencySetService;

/**
 * Обрабатывает запросы на изменение текущей валюты
 */
class CurrencyController extends Controller
{
    public function actions()
    {
        return [
            'set'=>[
                'class'=>PostRedirectAction::class,
                'service'=>new CurrencySetService()
            ],
        ];
    }
}
