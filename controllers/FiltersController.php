<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\controllers\AbstractBaseController;
use app\models\FiltersModel;
use app\helpers\{HashHelper,
    SessionHelper,
    StringHelper};

/**
 * Обрабатывает запросы, связанные с применением фильтров
 */
class FiltersController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос на применение фильтров
     * @return redirect
     */
    public function actionSet()
    {
        try {
            \Yii::configure(\Yii::$app->filters, ['scenario'=>FiltersModel::GET_FROM_FORM]);
            
            if (\Yii::$app->request->isPost && \Yii::$app->filters->load(\Yii::$app->request->post())) {
                if (\Yii::$app->filters->validate()) {
                    $key = StringHelper::cutPage(Url::previous());
                    SessionHelper::write($key, \Yii::$app->filters->attributes);
                }
            }
            
            return $this->redirect(isset($key) ? $key : Url::previous());
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на очистку фильтров
     * @return redirect
     */
    public function actionUnset()
    {
        try {
            if (\Yii::$app->request->isPost) {
                $key = StringHelper::cutPage(Url::previous());
                SessionHelper::remove([$key]);
                if (SessionHelper::has($key) === false) {
                    \Yii::$app->filters->clean();
                }
            }
            
            return $this->redirect(isset($key) ? $key : Url::previous());
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}