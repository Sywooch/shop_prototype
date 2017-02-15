<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\{AjaxAction,
    GetAction,
    RedirectAction};
use app\services\{UserGenerateService,
    UserRecoveryService,
    UserRecoveryPostService,
    UserRegistrationService,
    UserRegistrationPostService};
use app\handlers\{UserLoginPostRequestHandler,
    UserLoginRequestHandler,
    UserLogoutRequestHandler};

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
                'class'=>AjaxAction::class,
                'handler'=>new UserLogoutRequestHandler(),
            ],
            /*'registration'=>[
                'class'=>GetAction::class,
                'service'=>new UserRegistrationService(),
                'view'=>'registration-form.twig',
            ],
            'registration-post'=>[
                'class'=>AjaxAction::class,
                'service'=>new UserRegistrationPostService(),
            ],
            'recovery'=>[
                'class'=>GetAction::class,
                'service'=>new UserRecoveryService(),
                'view'=>'recovery-form.twig',
            ],
            'recovery-post'=>[
                'class'=>AjaxAction::class,
                'service'=>new UserRecoveryPostService(),
            ],
            'generate'=>[
                'class'=>GetAction::class,
                'service'=>new UserGenerateService(),
                'view'=>'generate.twig',
            ],*/
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
