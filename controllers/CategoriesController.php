<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use app\controllers\AbstractBaseController;
use app\helpers\MappersHelper;
use app\models\CategoriesModel;

/**
 * Управляет добавлением, удалением, обновлением товаров
 */
class CategoriesController extends AbstractBaseController
{
    /**
     * Возвращает массив объектов subcategory для category
     * @return json
     */
    public function actionGetSubcategoryAjax()
    {
        try {
            if (\Yii::$app->request->isAjax) {
                if (!\Yii::$app->request->post('categoriesId')) {
                    throw new ErrorException('Невозможно получить значение categoriesId!');
                }
                $response = \Yii::$app->response;
                $response->format = Response::FORMAT_JSON;
                if (!$subcategoriesArray = MappersHelper::getSubcategoryForCategoryList(new CategoriesModel(['id'=>\Yii::$app->request->post('categoriesId')]))) {
                    return false;
                }
                return ArrayHelper::map($subcategoriesArray, 'id', 'name');
            } else {
                throw new ErrorException('Неверный тип запроса!');
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
