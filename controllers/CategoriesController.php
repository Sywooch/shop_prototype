<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\AjaxAction;
use app\handlers\CategoriesGetSubcategoryRequestHandler;

/**
 * Обрабатывает запросы, связанные с категориями товаров
 */
class CategoriesController extends Controller
{
    public function actions()
    {
        return [
            'get-subcategory'=>[
                'class'=>AjaxAction::class,
                'handler'=>new CategoriesGetSubcategoryRequestHandler(),
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
                        'roles'=>['superUser'],
                    ],
                    [
                        'allow'=>false,
                        'roles'=>['?', '@']
                    ],
                ],
            ],
        ];
    }
}
