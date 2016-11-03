<?php

namespace app\controllers;

use app\controllers\AbstractControllerHelper;
use app\exceptions\ExceptionsTrait;
use app\models\SubcategoryModel;

/**
 * Коллекция сервис-методов AjaxController
 */
class AjaxControllerHelper extends AbstractControllerHelper
{
    /**
     * Конструирует response для AjaxController::actionSubcategory()
     * @return array
     */
    public static function subcategoryResponse(): array
    {
        try {
            if (!empty(\Yii::$app->request->post('categoryId'))) {
                $subcategoryQuery = SubcategoryModel::find();
                $subcategoryQuery->extendSelect(['id', 'name']);
                $subcategoryQuery->where(['[[subcategory.id_category]]'=>\Yii::$app->request->post('categoryId')]);
                $response = $subcategoryQuery->allMap('id', 'name');
                asort($response, SORT_STRING);
            }
            
            return $response ?? [];
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
