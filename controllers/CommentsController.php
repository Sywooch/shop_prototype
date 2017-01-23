<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\AjaxAction;
use app\services\CommentSaveService;

/**
 * Обрабатывает запросы, касающиеся комментариев к товарам
 */
class CommentsController extends Controller
{
    public function actions()
    {
        return [
            'save'=>[
                'class'=>AjaxAction::class,
                'service'=>new CommentSaveService(),
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
