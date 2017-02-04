<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\{AjaxAction,
    RedirectAction};
use app\services\{FiltersOrdersSetService,
    FiltersOrdersUnsetService,
    FiltersAdminProductsSetService,
    FiltersAdminProductsUnsetService,
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
            'orders-set'=>[
                'class'=>RedirectAction::class,
                'service'=>new FiltersOrdersSetService()
            ],
            'orders-unset'=>[
                'class'=>RedirectAction::class,
                'service'=>new FiltersOrdersUnsetService()
            ],
            'admin-products-set'=>[
                'class'=>RedirectAction::class,
                'service'=>new FiltersAdminProductsSetService()
            ],
            'admin-products-unset'=>[
                'class'=>RedirectAction::class,
                'service'=>new FiltersAdminProductsUnsetService()
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
