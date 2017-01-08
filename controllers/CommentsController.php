<?php

namespace app\controllers;

use yii\web\Controller;
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
}
