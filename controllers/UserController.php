<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\{FormAction,
    PostRedirectAction};
use app\services\{UserLoginService,
    UserGenerateService,
    UserLogoutService,
    UserRecoveryService,
    UserRegistrationService};

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
            'registration'=>[
                'class'=>FormAction::class,
                'service'=>new UserRegistrationService(),
                'view'=>'registration-form.twig',
            ],
            'recovery'=>[
                'class'=>FormAction::class,
                'service'=>new UserRecoveryService(),
                'view'=>'recovery-form.twig',
            ],
            'generate'=>[
                'class'=>FormAction::class,
                'service'=>new UserGenerateService(),
                'view'=>'generate-form.twig',
            ],
        ];
    }
}
