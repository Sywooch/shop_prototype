<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\FiltersModel;
use yii\helpers\Url;

/**
 * Обрабатывает запросы данных, к которым необходимо применить фильтры
 */
class FilterController extends Controller
{
    /**
     * Обрабатывает запрос на применение фильтров
     * @return redirect
     */
    public function actionAddFilters()
    {
        if (\Yii::$app->request->isPost && \Yii::$app->filters->load(\Yii::$app->request->post())) {
            if (\Yii::$app->filters->validate()) {
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
                $this->redirect(Url::to($urlArray));
            }
        }
    }
    
    public function behaviors()
    {
        return [
            ['class'=>'app\filters\ProductsListFilter'],
        ];
    }
}
