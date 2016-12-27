<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\SearchAction;
use app\services\UserLoginFormService;

/**
 * Обрабатывает запросы на аутентификацию пользователя
 */
class UserController extends Controller
{
    public function actions()
    {
        return [
            'login-form'=>[
                'class'=>SearchAction::class,
                'service'=>new UserLoginFormService(),
                'view'=>'login-form.twig',
            ],
        ];
    }
}
