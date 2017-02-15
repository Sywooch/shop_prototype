<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\{AjaxAction,
    GetAction};
use app\handlers\{MailingsIndexRequestHandler,
    MailingsSaveRequestHandler,
    MailingsUnsubscribePostRequestHandler,
    MailingsUnsubscribeRequestHandler};

/**
 * Обрабатывает запросы к настройкам аккаунта
 */
class MailingsController extends Controller
{
    public function actions()
    {
        return [
            'index'=>[
                'class'=>GetAction::class,
                'handler'=>new MailingsIndexRequestHandler(),
                'view'=>'index.twig',
            ],
            'save'=>[
                'class'=>AjaxAction::class,
                'handler'=>new MailingsSaveRequestHandler(),
            ],
            'unsubscribe'=>[
                'class'=>GetAction::class,
                'handler'=>new MailingsUnsubscribeRequestHandler(),
                'view'=>'unsubscribe.twig',
            ],
            'unsubscribe-post'=>[
                'class'=>AjaxAction::class,
                'handler'=>new MailingsUnsubscribePostRequestHandler(),
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
                        'actions'=>['save', 'unsubscribe-post'],
                        'verbs'=>['GET']
                    ],
                    [
                        'allow'=>true,
                        'roles'=>['?', '@']
                    ],
                ],
            ],
        ];
    }
}
