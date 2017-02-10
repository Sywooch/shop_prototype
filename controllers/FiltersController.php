<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\{AjaxAction,
    RedirectAction};
use app\handlers\{FiltersAdminProductsSetRequestHandler,
    FiltersAdminProductsUnsetRequestHandler,
    FiltersOrdersSetRequestHandler,
    FiltersOrdersUnsetRequestHandler,
    FiltersSetRequestHandler,
    FiltersUnsetRequestHandler};

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
                'handler'=>new FiltersSetRequestHandler()
            ],
            'unset'=>[
                'class'=>RedirectAction::class,
                'handler'=>new FiltersUnsetRequestHandler()
            ],
            'orders-set'=>[
                'class'=>RedirectAction::class,
                'handler'=>new FiltersOrdersSetRequestHandler()
            ],
            'orders-unset'=>[
                'class'=>RedirectAction::class,
                'handler'=>new FiltersOrdersUnsetRequestHandler()
            ],
            'admin-products-set'=>[
                'class'=>RedirectAction::class,
                'handler'=>new FiltersAdminProductsSetRequestHandler()
            ],
            'admin-products-unset'=>[
                'class'=>RedirectAction::class,
                'handler'=>new FiltersAdminProductsUnsetRequestHandler()
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
