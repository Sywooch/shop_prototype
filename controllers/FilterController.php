<?php

namespace app\controllers;

use app\controllers\AbstractBaseProductsController;
use app\models\FiltersModel;
use yii\helpers\Url;
use app\helpers\SessionHelper;

/**
 * Обрабатывает запросы данных, к которым необходимо применить фильтры
 */
class FilterController extends AbstractBaseProductsController
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
                    $urlArray = $this->getRedirectUrl();
                }
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $this->redirect(Url::to($urlArray));
    }
    
    /**
     * Обрабатывает запрос на очистку фильтров
     * @return redirect
     */
    public function actionCleanFilters()
    {
        try {
            if (\Yii::$app->request->isPost && \Yii::$app->filters->load(\Yii::$app->request->post())) {
                if (\Yii::$app->filters->validate()) {
                    SessionHelper::removeVarFromSession([\Yii::$app->params['filtersKeyInSession']]);
                    $urlArray = $this->getRedirectUrl();
                }
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $this->redirect(Url::to($urlArray));
    }
    
    /**
     * Формирует URL для редиректа
     * @return string
     */
    private function getRedirectUrl()
    {
        try {
            if (!empty(\Yii::$app->filters->search)) {
                $urlArray = ['products-list/search', \Yii::$app->params['searchKey']=>\Yii::$app->filters->search];
            } else {
                $urlArray = ['products-list/index'];
                if (!empty(\Yii::$app->filters->categories)) {
                    $urlArray = array_merge($urlArray, [\Yii::$app->params['categoryKey']=>\Yii::$app->filters->categories]);
                }
                if (!empty(\Yii::$app->filters->subcategory)) {
                    $urlArray = array_merge($urlArray, [\Yii::$app->params['subCategoryKey']=>\Yii::$app->filters->subcategory]);
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $urlArray;
    }
    
    public function behaviors()
    {
        return [
            ['class'=>'app\filters\ProductsListFilter'],
        ];
    }
}
