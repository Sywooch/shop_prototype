<?php

namespace app\controllers;

use app\controllers\AbstractControllerHelper;
use app\exceptions\ExceptionsTrait;
use app\helpers\{SessionHelper,
    StringHelper,
    UrlHelper};
use app\models\FiltersModel;

/**
 * Коллекция сервис-методов FiltersController
 */
class FiltersControllerHelper extends AbstractControllerHelper
{
    /**
     * Пишет в сессию значения фильтров для FiltersController::actionSet()
     * @return string
     */
    public static function sessionSet(): string
    {
        try {
            \Yii::configure(\Yii::$app->filters, ['scenario'=>FiltersModel::GET_FROM_FORM]);
            
            if (\Yii::$app->filters->load(\Yii::$app->request->post())) {
                $key = StringHelper::cutPage(UrlHelper::previous('shop'));
                if (!empty($key)) {
                    SessionHelper::write($key, \Yii::$app->filters->toArray());
                }
            }
            
            return $key ?? '';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Удаляет из сессии значения фильтров для FiltersController::actionUnset()
     * @return string
     */
    public static function sessionUnset(): string
    {
        try {
            $key = StringHelper::cutPage(UrlHelper::previous('shop'));
            if (!empty($key)) {
                SessionHelper::remove([$key]);
                if (SessionHelper::has($key) === false) {
                    \Yii::$app->filters->clean();
                }
            }
            
            return $key;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
