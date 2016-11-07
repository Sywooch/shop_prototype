<?php

namespace app\controllers;

use yii\web\Response;
use app\controllers\AbstractControllerHelper;
use app\exceptions\ExceptionsTrait;

/**
 * Коллекция сервис-методов AjaxController
 */
class AjaxControllerHelper extends AbstractControllerHelper
{
    /**
     * Конструирует response для AjaxController::actionSubcategory()
     * @return array
     */
    public static function subcategoryAjax(): array
    {
        try {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            
            if (!empty(\Yii::$app->request->post('categoryId'))) {
                $response = self::getSubcategory(\Yii::$app->request->post('categoryId'), true);
            }
            
            return $response ?? [];
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
