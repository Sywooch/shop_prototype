<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\controllers\AbstractBaseController;
use app\models\{ProductsModel,
    PurchasesModel};
use app\helpers\{InstancesHelper,
    SessionHelper,
    UrlHelper};

/**
 * Обрабатывает запросы, связанные с данными корзины
 */
class CartController extends AbstractBaseController
{
    public function actionIndex()
    {
        try {
            if (empty(\Yii::$app->params['cartArray'])) {
                return $this->redirect(UrlHelper::previous('shop'));
            }
            
            $renderArray = InstancesHelper::getInstances();
            if (!is_array($renderArray) || empty($renderArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray']));
            }
            
            if (!empty(\Yii::$app->params['cartArray'])) {
                foreach (\Yii::$app->params['cartArray'] as $purchase) {
                    $renderArray['purchasesList'][] = \Yii::configure((new PurchasesModel()), array_filter($purchase, function($key) {
                        return array_key_exists($key, (new PurchasesModel())->attributes);
                    }, ARRAY_FILTER_USE_KEY));
                }
            }
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/cart/index'], 'label'=>\Yii::t('base', 'Cart')];
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('cart.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
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
            
            return $this->redirect(UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            if (YII_ENV_DEV) {
                $this->throwException($t, __METHOD__);
            } else {
                return $this->redirect(UrlHelper::previous('shop'));
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
            
            return $this->redirect(UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            if (YII_ENV_DEV) {
                $this->throwException($t, __METHOD__);
            } else {
                return $this->redirect(UrlHelper::previous('shop'));
            }
        }
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\CurrencyFilter',
                'only'=>['index']
            ],
            [
                'class'=>'app\filters\CartFilter',
                'only'=>['index']
            ],
        ];
    }
}
