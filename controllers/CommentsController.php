<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\PostRedirectAction;
use app\services\CommentsSaveService;

/**
 * Обрабатывает запросы, касающиеся комментариев к товарам
 */
class CommentsController extends Controller
{
    public function actions()
    {
        return [
            'save'=>[
                'class'=>PostRedirectAction::class,
                'service'=>new CommentsSaveService()
            ],
        ];
    }
}
