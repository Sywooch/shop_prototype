<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\controllers\{AbstractBaseController,
    MailingControllerHelper};
use app\helpers\UrlHelper;

/**
 * Обрабатывает запросы, связанные с подписками на рассылки
 */
class MailingController extends AbstractBaseController
{
    /**
     * Добавляет подписчика к рассылкам
     */
    public function actionIndex()
    {
        try {
            if (\Yii::$app->request->isPost) {
                MailingControllerHelper::indexPost();
            }
            
            $renderArray = MailingControllerHelper::indexGet();
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('add-subscriber.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
