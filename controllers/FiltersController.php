<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\{AbstractBaseController,
    FiltersControllerHelper};
use app\helpers\UrlHelper;

/**
 * Обрабатывает запросы, связанные с применением фильтров
 */
class FiltersController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос на применение фильтров
     */
    public function actionSet()
    {
        try {
            if (\Yii::$app->request->isPost) {
                $key = FiltersControllerHelper::sessionSet();
            }
            
            return $this->redirect(!empty($key) ? $key : UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на очистку фильтров
     */
    public function actionUnset()
    {
        try {
            if (\Yii::$app->request->isPost) {
                $key = FiltersControllerHelper::sessionUnset();
            }
            
            return $this->redirect(!empty($key) ? $key : UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
