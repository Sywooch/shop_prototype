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
        $model = new FiltersModel(['scenario'=>FiltersModel::GET_FROM_FORM]);
        
        if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                $urlArray = ['products-list/index'];
                if (!empty($model['categories'])) {
                    $urlArray = array_merge($urlArray, ['categories'=>$model['categories']]);
                }
                if (!empty($model['subcategory'])) {
                    $urlArray = array_merge($urlArray, ['subcategory'=>$model['subcategory']]);
                }
                \Yii::$app->params['productsFiltersArray'] = $model->attributes;
                $this->redirect(Url::to($urlArray));
            }
        }
    }
}
