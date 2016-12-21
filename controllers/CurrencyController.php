<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use app\actions\PostRedirectAction;
use app\services\CurrencySetService;

/**
 * Обрабатывает запросы на изменение текущей валюты
 */
class CurrencyController extends AbstractBaseController
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
