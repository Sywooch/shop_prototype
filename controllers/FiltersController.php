<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\models\FiltersModel;
use app\helpers\{HashHelper,
    SessionHelper,
    StringHelper,
    UrlHelper};

/**
 * Обрабатывает запросы, связанные с применением фильтров
 */
class FiltersController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос на применение фильтров
     * @return redirect
     */
    public function actionSet()
    {
        try {
            \Yii::configure(\Yii::$app->filters, ['scenario'=>FiltersModel::GET_FROM_FORM]);
            
            if (\Yii::$app->request->isPost && \Yii::$app->filters->load(\Yii::$app->request->post())) {
                if (\Yii::$app->filters->validate('shop')) {
                    $key = StringHelper::cutPage(UrlHelper::previous('shop'));
                    if (!is_string($key) || empty($key)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'string $key']));
                    }
                    SessionHelper::write($key, \Yii::$app->filters->attributes);
                }
            }
            
            return $this->redirect($key ?? UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            if (YII_ENV_DEV) {
                $this->throwException($t, __METHOD__);
            } else {
                return $this->redirect($key ?? UrlHelper::previous('shop'));
            }
        }
    }
    
    /**
     * Обрабатывает запрос на очистку фильтров
     * @return redirect
     */
    public function actionUnset()
    {
        try {
            if (\Yii::$app->request->isPost) {
                $key = StringHelper::cutPage(UrlHelper::previous('shop'));
                if (!is_string($key) || empty($key)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'string $key']));
                }
                SessionHelper::remove([$key]);
                if (SessionHelper::has($key) === false) {
                    \Yii::$app->filters->clean();
                }
            }
            
            return $this->redirect($key ?? UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            if (YII_ENV_DEV) {
                $this->throwException($t, __METHOD__);
            } else {
                return $this->redirect($key ?? UrlHelper::previous('shop'));
            }
        }
    }
}
