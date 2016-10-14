<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\controllers\AbstractBaseController;
use app\models\FiltersModel;
use app\helpers\{HashHelper,
    SessionHelper};

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
                    $key = Url::previous();
                    if (preg_match('/(.*)-\d+$/', $key, $matches) === 1) {
                        $key = $matches[1];
                    }
                    SessionHelper::write(HashHelper::createHash([$key]), \Yii::$app->filters->attributes);
                }
            }
            
            return $this->redirect(Url::previous());
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
