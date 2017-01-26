<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\{AjaxAction,
    RedirectAction};
use app\services\{FiltersAdminOrdersSetService,
    FiltersAdminOrdersUnsetService,
    FiltersSetService,
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
            'admin-orders-set'=>[
                'class'=>RedirectAction::class,
                'service'=>new FiltersAdminOrdersSetService()
            ],
            'admin-orders-unset'=>[
                'class'=>RedirectAction::class,
                'service'=>new FiltersAdminOrdersUnsetService()
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
