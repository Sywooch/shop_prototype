<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\controllers\AbstractBaseController;
use app\models\FiltersModel;
use app\helpers\{HashHelper,
    SessionHelper,
    StringHelper};

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
                if (\Yii::$app->filters->validate()) {
                    $key = StringHelper::cutPage(Url::previous());
                    if (!is_string($key) || empty($key)) {
                        if (YII_ENV_DEV) {
                            throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'string $key']));
                        } else {
                            $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'string $key']), __METHOD__);
                        }
                    } else {
                        SessionHelper::write($key, \Yii::$app->filters->attributes);
                    }
                }
            }
            
            return $this->redirect($key ?? Url::previous());
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
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
                $key = StringHelper::cutPage(Url::previous());
                if (!is_string($key) || empty($key)) {
                    if (YII_ENV_DEV) {
                        throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'string $key']));
                    } else {
                        $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'string $key']), __METHOD__);
                    }
                } else {
                    SessionHelper::remove([$key]);
                    if (SessionHelper::has($key) === false) {
                        \Yii::$app->filters->clean();
                    }
                }
            }
            
            return $this->redirect($key ?? Url::previous());
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
