<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\{AjaxAction,
    GetAction};
use app\services\{MailingsIndexService,
    MailingsSaveService,
    MailingsUnsubscribeService};

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
            /*'unsubscribe-post'=>[
                'class'=>AjaxAction::class,
                'service'=>new MailingsUnsubscribePostService(),
            ],*/
        ];
    }
}
