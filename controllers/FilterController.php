<?php

namespace app\controllers;

use yii\helpers\Url;
use yii\base\ErrorException;
use app\helpers\SessionHelper;
use app\helpers\RedirectHelper;
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
            if (\Yii::$app->request->isPost && \Yii::$app->filters->load(\Yii::$app->request->post())) {
                if (\Yii::$app->filters->validate()) {
                    $urlArray = RedirectHelper::getRedirectUrl(\Yii::$app->filters);
                    if (!is_array($urlArray) || empty($urlArray)) {
                        throw new ErrorException('Ошибка при получении данных для редиректа!');
                    }
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
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
            if (empty(\Yii::$app->params['filtersKeyInSession'])) {
                throw new ErrorException('Не установлена переменная filtersKeyInSession!');
            }
            if (\Yii::$app->request->isPost && \Yii::$app->filters->load(\Yii::$app->request->post())) {
                if (\Yii::$app->filters->validate()) {
                    if (!SessionHelper::removeVarFromSession([\Yii::$app->params['filtersKeyInSession']])) {
                        throw new ErrorException('Ошибка при удалении переменной из сесии!');
                    }
                    if (!\Yii::$app->filters->clean()) {
                        throw new ErrorException('Ошибка при очистке фильтров!');
                    }
                    $urlArray = RedirectHelper::getRedirectUrl(\Yii::$app->filters);
                    if (!is_array($urlArray) || empty($urlArray)) {
                        throw new ErrorException('Ошибка при получении данных для редиректа!');
                    }
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
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
            ['class'=>'app\filters\ProductsListFilter'],
        ];
    }
}
