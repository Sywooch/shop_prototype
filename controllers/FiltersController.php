<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\{AbstractBaseController,
    FiltersControllerHelper};
use app\helpers\UrlHelper;
use app\actions\SaveRedirectAction;
use app\services\FiltersSetService;

/**
 * Обрабатывает запросы, связанные с применением фильтров
 */
class FiltersController extends AbstractBaseController
{
    public function actions()
    {
        return [
            'set'=>[
                'class'=>SaveRedirectAction::class,
                'service'=>new FiltersSetService()
            ],
        ];
    }
    
    /**
     * Обрабатывает запрос на очистку фильтров
     */
    public function actionUnset()
    {
        try {
            if (\Yii::$app->request->isPost) {
                $key = FiltersControllerHelper::unsetPost();
            }
            
            return $this->redirect(!empty($key) ? $key : UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
