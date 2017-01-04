<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\RedirectAction;
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
                'class'=>RedirectAction::class,
                'service'=>new CommentsSaveService(),
            ],
        ];
    }
}
