<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\helpers\{ArrayHelper,
    Url};
use app\controllers\AbstractBaseController;
use app\models\SubcategoryModel;

/**
 * Управляет обработкой запросов данных подкатегорий
 */
class SubcategoryController extends AbstractBaseController
{
    /**
     * Обрабатывает Ajax запрос на получений подкатегорий для категории
     */
    public function actionGetForCategory()
    {
        try {
            if (!\Yii::$app->request->isAjax || empty(\Yii::$app->request->post('categoryId'))) {
                $this->redirect(Url::to(['products-list/index']));
            }
            
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $subcategoryQuery = SubcategoryModel::find();
            $subcategoryQuery->extendSelect(['id', 'name']);
            $subcategoryQuery->where(['[[subcategory.id_category]]'=>\Yii::$app->request->post('categoryId')]);
            $subcategoryQuery->asArray();
            $subcategoryArray = $subcategoryQuery->all();
            if (!empty($subcategoryArray)) {
                $result = ArrayHelper::map($subcategoryArray, 'id', 'name');
            }
            
            return !empty($result) ? $result : [];
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
