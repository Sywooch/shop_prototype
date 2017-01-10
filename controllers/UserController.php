<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\{AjaxAction,
    GetAction,
    RedirectAction};
use app\services\{UserLoginService,
    UserLoginFormService,
    UserGenerateService,
    UserLogoutService,
    UserRecoveryFormService,
    UserRecoveryService,
    UserRegistrationFormService,
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
                'class'=>GetAction::class,
                'service'=>new UserLoginFormService(),
                'view'=>'login-form.twig',
            ],
            'login-post'=>[
                'class'=>RedirectAction::class,
                'service'=>new UserLoginService(),
            ],
            'logout'=>[
                'class'=>RedirectAction::class,
                'service'=>new UserLogoutService(),
            ],
            'registration'=>[
                'class'=>GetAction::class,
                'service'=>new UserRegistrationFormService(),
                'view'=>'registration-form.twig',
            ],
            'registration-post'=>[
                'class'=>AjaxAction::class,
                'service'=>new UserRegistrationService(),
            ],
            'recovery'=>[
                'class'=>GetAction::class,
                'service'=>new UserRecoveryFormService(),
                'view'=>'recovery-form.twig',
            ],
            'recovery-post'=>[
                'class'=>AjaxAction::class,
                'service'=>new UserRecoveryService(),
            ],
            'generate'=>[
                'class'=>GetAction::class,
                'service'=>new UserGenerateService(),
                'view'=>'generate.twig',
            ],
        ];
    }
}
