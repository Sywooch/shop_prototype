<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\{BaseAction,
    RedirectAction};
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
                'class'=>BaseAction::class,
                'service'=>new UserLoginService(),
                'view'=>'login-form.twig',
            ],
            'logout'=>[
                'class'=>RedirectAction::class,
                'service'=>new UserLogoutService(),
            ],
            'registration'=>[
                'class'=>BaseAction::class,
                'service'=>new UserRegistrationService(),
                'view'=>'registration-form.twig',
            ],
            'recovery'=>[
                'class'=>BaseAction::class,
                'service'=>new UserRecoveryService(),
                'view'=>'recovery-form.twig',
            ],
            'generate'=>[
                'class'=>BaseAction::class,
                'service'=>new UserGenerateService(),
                'view'=>'generate-form.twig',
            ],
        ];
    }
}
