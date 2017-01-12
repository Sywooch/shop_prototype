<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\{AjaxAction,
    RedirectAction};
use app\services\{FiltersSetService,
    FiltersSetAjaxService,
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
                'class'=>AjaxAction::class,
                'service'=>new FiltersSetAjaxService()
            ],
            'unset'=>[
                'class'=>RedirectAction::class,
                'service'=>new FiltersUnsetService()
            ],
        ];
    }
}
