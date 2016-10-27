<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\controllers\AbstractBaseController;
use app\models\{ProductsModel,
    PurchasesModel};
use app\helpers\SessionHelper;

/**
 * Обрабатывает запросы, связанные с данными корзины
 */
class CartController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос на добавление товара в корзину
     * @return string
     */
    public function actionSet()
    {
        try {
            $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
            $rawProductsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_TO_CART]);
            
            if (\Yii::$app->request->isPost && $rawPurchasesModel->load(\Yii::$app->request->post()) && $rawProductsModel->load(\Yii::$app->request->post())) {
                if ($rawPurchasesModel->validate() && $rawProductsModel->validate()) {
                    $cartArray = SessionHelper::read(\Yii::$app->params['cartKey']) ?? [];
                    $cartArray[] = array_filter(ArrayHelper::merge($rawPurchasesModel->attributes, $rawProductsModel->attributes));
                    SessionHelper::write(\Yii::$app->params['cartKey'], $cartArray);
                } else {
                    $this->writeErrorInLogs(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'Model::validate']), __METHOD__);
                }
            }
            
            return $this->redirect(Url::previous());
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            if (YII_ENV_DEV) {
                $this->throwException($t, __METHOD__);
            } else {
                return $this->redirect(Url::previous());
            }
        }
    }
    
    /**
     * Обрабатывает запрос на удаление всех товаров из корзины
     * @return string
     */
    public function actionClean()
    {
        try {
            if (\Yii::$app->request->isPost) {
                SessionHelper::remove([\Yii::$app->params['cartKey']]);
                if (SessionHelper::has(\Yii::$app->params['cartKey']) === false) {
                    \Yii::$app->params['cartArray'] = [];
                }
            }
            
            return $this->redirect(Url::previous());
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            if (YII_ENV_DEV) {
                $this->throwException($t, __METHOD__);
            } else {
                return $this->redirect(Url::previous());
            }
        }
    }
}
