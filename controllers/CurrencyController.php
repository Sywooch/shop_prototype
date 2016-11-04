<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\{AbstractBaseController,
    CurrencyControllerHelper};
use app\helpers\UrlHelper;

/**
 * Обрабатывает запросы на изменение текущей валюты
 */
class CurrencyController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос на изменение текущей валюты
     */
    public function actionSet()
    {
        try {
            if (\Yii::$app->request->isPost) {
                CurrencyControllerHelper::setPost();
            }
            
            return $this->redirect(UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
