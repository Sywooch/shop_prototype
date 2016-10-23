<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\web\Response;
use app\controllers\AbstractBaseController;
use app\models\SubcategoryModel;

/**
 * Управляет обработкой запросов данных подкатегорий
 */
class AjaxController extends AbstractBaseController
{
    /**
     * Обрабатывает Ajax запрос на получений подкатегорий для категории
     */
    public function actionGetSubcategory()
    {
        try {
            if (!\Yii::$app->request->isAjax || empty(\Yii::$app->request->post('categoryId'))) {
                $this->redirect(Url::to(['/products-list/index']));
            }
            
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $subcategoryQuery = SubcategoryModel::find();
            $subcategoryQuery->extendSelect(['id', 'name']);
            $subcategoryQuery->where(['[[subcategory.id_category]]'=>\Yii::$app->request->post('categoryId')]);
            $subcategoryArray = $subcategoryQuery->allMap('id', 'name');
            if (!is_array($subcategoryArray) || empty($subcategoryArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $subcategoryArray']));
            }
            
            return $subcategoryArray;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
