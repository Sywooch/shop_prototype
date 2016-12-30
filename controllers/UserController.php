<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\{FormAction,
    PostRedirectAction};
use app\services\{UserLoginService,
    UserLogoutService};

/**
 * Обрабатывает запросы на аутентификацию пользователя
 */
class UserController extends Controller
{
    public function actions()
    {
        return [
            'login'=>[
                'class'=>FormAction::class,
                'service'=>new UserLoginService(),
                'view'=>'login-form.twig',
            ],
            'logout'=>[
                'class'=>PostRedirectAction::class,
                'service'=>new UserLogoutService(),
            ],
        ];
    }
}
