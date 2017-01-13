<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\{AjaxAction,
    RedirectAction};
use app\services\{FiltersSetService,
    FiltersUnsetService};

/**
 * Обрабатывает запросы, связанные с применением фильтров
 */
class FiltersController extends Controller
{
    public function actions()
    {
        return [
            'set'=>[
                'class'=>RedirectAction::class,
                'service'=>new FiltersSetService()
            ],
            'unset'=>[
                'class'=>RedirectAction::class,
                'service'=>new FiltersUnsetService()
            ],
        ];
    }
}
