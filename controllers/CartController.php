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
                    if (!$this->write($rawPurchasesModel, $rawProductsModel)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'CartController::write']));
                    }
                } else {
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'Model::validate']), __METHOD__);
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
    
    /**
     * Обрабатывает запрос на обновление характеристик товара в корзине
     * @return string
     */
    public function actionUpdate()
    {
        try {
            $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
            $rawProductsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_TO_CART]);
            
            if (\Yii::$app->request->isPost && $rawPurchasesModel->load(\Yii::$app->request->post()) && $rawProductsModel->load(\Yii::$app->request->post())) {
                if ($rawPurchasesModel->validate() && $rawProductsModel->validate()) {
                    if (array_key_exists($rawPurchasesModel->id_product, (\Yii::$app->params['cartArray']))) {
                        unset(\Yii::$app->params['cartArray'][$rawPurchasesModel->id_product]);
                        if (!$this->write($rawPurchasesModel, $rawProductsModel)) {
                            throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'CartController::write']));
                        }
                    }
                } else {
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PurchasesModel::validate']), __METHOD__);
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
     * Обрабатывает запрос на удаление 1 товара из корзины
     * @return string
     */
    public function actionDelete()
    {
        try {
            $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_DELETE_FROM_CART]);
            
            if (\Yii::$app->request->isPost && $rawPurchasesModel->load(\Yii::$app->request->post())) {
                if ($rawPurchasesModel->validate()) {
                    if (array_key_exists($rawPurchasesModel->id_product, (\Yii::$app->params['cartArray']))) {
                        unset(\Yii::$app->params['cartArray'][$rawPurchasesModel->id_product]);
                        if (empty(\Yii::$app->params['cartArray'])) {
                            SessionHelper::remove([\Yii::$app->params['cartKey']]);
                            return $this->redirect(Url::to(['/products-list/index']));
                        }
                        SessionHelper::write(\Yii::$app->params['cartKey'], \Yii::$app->params['cartArray']);
                    }
                } else {
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PurchasesModel::validate']), __METHOD__);
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
     * Пишет в сессию массив данных о товарах в корзине
     * @param object $rawPurchasesModel объект PurchasesModel
     * @param object $rawProductsModel объект ProductsModel
     * @return bool
     */
    private function write(PurchasesModel $rawPurchasesModel, ProductsModel $rawProductsModel): bool
    {
        try {
            \Yii::$app->params['cartArray'][$rawPurchasesModel->id_product] = array_filter(ArrayHelper::merge($rawPurchasesModel->attributes, $rawProductsModel->attributes));
            SessionHelper::write(\Yii::$app->params['cartKey'], \Yii::$app->params['cartArray']);
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
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
                'only'=>['index', 'set', 'update', 'delete']
            ],
        ];
    }
}
