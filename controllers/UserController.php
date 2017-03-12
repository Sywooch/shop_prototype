<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\{AjaxAction,
    GetAction,
    RedirectAction};
use app\handlers\{UserGenerateRequestHandler,
    UserLoginPostRequestHandler,
    UserLoginRequestHandler,
    UserLogoutRequestHandler,
    UserRecoveryPostRequestHandler,
    UserRecoveryRequestHandler,
    UserRegistrationPostRequestHandler,
    UserRegistrationRequestHandler};

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
                'handler'=>new UserLoginRequestHandler(),
                'view'=>'login-form.twig',
            ],
            'login-post'=>[
                'class'=>AjaxAction::class,
                'handler'=>new UserLoginPostRequestHandler(),
            ],
            'logout'=>[
                'class'=>RedirectAction::class,
                'handler'=>new UserLogoutRequestHandler(),
            ],
            'registration'=>[
                'class'=>GetAction::class,
                'handler'=>new UserRegistrationRequestHandler(),
                'view'=>'registration-form.twig',
            ],
            'registration-post'=>[
                'class'=>AjaxAction::class,
                'handler'=>new UserRegistrationPostRequestHandler(),
            ],
            'recovery'=>[
                'class'=>GetAction::class,
                'handler'=>new UserRecoveryRequestHandler(),
                'view'=>'recovery-form.twig',
            ],
            'recovery-post'=>[
                'class'=>AjaxAction::class,
                'handler'=>new UserRecoveryPostRequestHandler(),
            ],
            'generate'=>[
                'class'=>GetAction::class,
                'handler'=>new UserGenerateRequestHandler(),
                'view'=>'generate.twig',
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
                        'actions'=>['login-post', 'logout', 'registration-post', 'recovery-post'],
                        'verbs'=>['GET'],
                    ],
                    [
                        'allow'=>false,
                        'actions'=>['login', 'login-post', 'registration', 'registration-post', 'recovery', 'recovery-post', 'generate'],
                        'roles'=>['@']
                    ],
                    [
                        'allow'=>true,
                        'roles'=>['?', '@'],
                    ],
                ],
            ],
        ];
    }
}
