<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\RedirectAction;
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
                'class'=>RedirectAction::class,
                'service'=>new CurrencySetService()
            ],
        ];
    }
}
