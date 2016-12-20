<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use app\actions\PostRedirectAction;
use app\services\{FiltersCleanService,
    FiltersSetService};

/**
 * Обрабатывает запросы, связанные с применением фильтров
 */
class FiltersController extends AbstractBaseController
{
    public function actions()
    {
        return [
            'set'=>[
                'class'=>PostRedirectAction::class,
                'service'=>new FiltersSetService()
            ],
            /*'unset'=>[
                'class'=>PostRedirectAction::class,
                'service'=>new FiltersCleanService()
            ],*/
        ];
    }
}
