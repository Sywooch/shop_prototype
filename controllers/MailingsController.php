<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\{AjaxAction,
    GetAction};
use app\services\{MailingsIndexService,
    MailingsSaveService,
    MailingsUnsubscribeService,
    MailingsUnsubscribePostService};

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
                'service'=>new MailingsIndexService(),
                'view'=>'index.twig',
            ],
            'save'=>[
                'class'=>AjaxAction::class,
                'service'=>new MailingsSaveService(),
            ],
            'unsubscribe'=>[
                'class'=>GetAction::class,
                'service'=>new MailingsUnsubscribeService(),
                'view'=>'unsubscribe.twig',
            ],
            'unsubscribe-post'=>[
                'class'=>AjaxAction::class,
                'service'=>new MailingsUnsubscribePostService(),
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
