<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\Response;
use app\controllers\{AbstractBaseController,
    AjaxControllerHelper};

/**
 * Управляет обработкой Ajax запросов
 */
class AjaxController extends AbstractBaseController
{
    /**
     * Обрабатывает Ajax запрос на получений подкатегорий для категории
     */
    public function actionSubcategory()
    {
        try {
            if (!\Yii::$app->request->isAjax) {
                return $this->redirect(Url::to(['/products-list/index']));
            }
            
            \Yii::$app->response->format = Response::FORMAT_JSON;
            
            $response = AjaxControllerHelper::subcategoryResponse();
            
            return $response;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
        }
    }
}
