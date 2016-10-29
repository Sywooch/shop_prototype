<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\helpers\Url;
use app\controllers\AbstractBaseController;
use app\models\SubcategoryModel;

/**
 * Управляет обработкой Ajax запросов
 */
class AjaxController extends AbstractBaseController
{
    /**
     * Обрабатывает Ajax запрос на получений подкатегорий для категории
     * @return mixed $response
     */
    public function actionGetSubcategory()
    {
        try {
            if (!\Yii::$app->request->isAjax) {
                return $this->redirect(Url::to(['/products-list/index']));
            }
            
            \Yii::$app->response->format = Response::FORMAT_JSON;
            
            if (!empty(\Yii::$app->request->post('categoryId'))) {
                $subcategoryQuery = SubcategoryModel::find();
                $subcategoryQuery->extendSelect(['id', 'name']);
                $subcategoryQuery->where(['[[subcategory.id_category]]'=>\Yii::$app->request->post('categoryId')]);
                $response = $subcategoryQuery->allMap('id', 'name');
                if (!is_array($response)) {
                    $response = [];
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $subcategoryArray']));
                }
                asort($response, SORT_STRING);
            }
            
            return $response ?? [];
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
        }
    }
}
