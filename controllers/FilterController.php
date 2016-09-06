<?php

namespace app\controllers;

use yii\helpers\Url;
use yii\base\ErrorException;
use app\helpers\{HashHelper,
    HrefHelper,
    SessionHelper};
use app\controllers\AbstractBaseController;

/**
 * Обрабатывает запросы данных, к которым необходимо применить фильтры
 */
class FilterController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос на применение фильтров
     * @return redirect
     */
    public function actionAddFilters()
    {
        try {
            $urlArray = ['products-list/index'];
            if (\Yii::$app->request->isPost && \Yii::$app->filters->load(\Yii::$app->request->post())) {
                if (\Yii::$app->filters->validate()) {
                    $urlArray = HrefHelper::createHrefFromFilter($urlArray);
                }
            }
            return $this->redirect(Url::to($urlArray));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на очистку фильтров
     * @return redirect
     */
    public function actionCleanFilters()
    {
        try {
            $urlArray = ['products-list/index'];
            if (\Yii::$app->request->isPost && \Yii::$app->filters->load(\Yii::$app->request->post())) {
                if (\Yii::$app->filters->validate()) {
                    if (!SessionHelper::removeVarFromSession([\Yii::$app->params['filtersKeyInSession'] . '.' . HashHelper::createHashFromFilters()])) {
                        throw new ErrorException('Ошибка при удалении фильтров из сесии!');
                    }
                    $urlArray = HrefHelper::createHrefFromFilter($urlArray);
                    \Yii::$app->filters->clean();
                    \Yii::$app->filters->cleanOther();
                }
            }
            return $this->redirect(Url::to($urlArray));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\SetFiltersForProducts',
                'only'=>['add-filters'],
            ],
        ];
    }
}
