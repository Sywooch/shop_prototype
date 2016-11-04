<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use app\controllers\AbstractControllerHelper;
use app\exceptions\ExceptionsTrait;
use app\models\{PurchasesModel,
    ProductsModel};
use app\helpers\{HashHelper,
    InstancesHelper,
    SessionHelper};
use app\widgets\CartWidget;

/**
 * Коллекция сервис-методов CartController
 */
class CartControllerHelper extends AbstractControllerHelper
{
    /**
     * Конструирует данные для CartController::actionIndex()
     * @return array
     */
    public static function indexGet(): array
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            
            $productsQuery = ProductsModel::find();
            $productsQuery->extendSelect(['id', 'name', 'short_description', 'price', 'images', 'seocode']);
            $productsQuery->where(['[[products.id]]'=>ArrayHelper::getColumn(\Yii::$app->params['cartArray'], 'id_product')]);
            $productsQuery->with(['colors', 'sizes']);
            $productsQuery->asArray();
            $productsArray = $productsQuery->all();
            $productsArray = ArrayHelper::index($productsArray, 'id');
            
            foreach (\Yii::$app->params['cartArray'] as $hash=>$purchase) {
                $renderArray['purchasesList'][$hash] = [
                    'purchase'=>\Yii::configure((new PurchasesModel()), $purchase), 
                    'product'=>$productsArray[$purchase['id_product']],
                ];
            }
            
            self::breadcrumbs();
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает POST запрос для CartController::actionSet()
     */
    public static function setPost()
    {
        try {
            $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
            
            if ($rawPurchasesModel->load(\Yii::$app->request->post())) {
                if ($rawPurchasesModel->validate()) {
                    self::write($rawPurchasesModel->toArray());
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает Ajax запрос для CartController::actionSet()
     * @return bool
     */
    public static function setAjax(): string
    {
        try {
            $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
            
            if ($rawPurchasesModel->load(\Yii::$app->request->post())) {
                if ($rawPurchasesModel->validate()) {
                    self::write($rawPurchasesModel->toArray());
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return CartWidget::widget();
                }
            }
            
            return '';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает POST запрос для CartController::actionClean()
     */
    public static function cleanPost()
    {
        try {
            $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            $customerKey = HashHelper::createHash([\Yii::$app->params['customerKey'], \Yii::$app->user->id ?? '']);
            SessionHelper::remove([$cartKey, $customerKey]);
            if (SessionHelper::has($cartKey) === false && SessionHelper::has($customerKey) === false) {
                \Yii::$app->params['cartArray'] = [];
                \Yii::$app->params['customerArray'] = [];
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает POST запрос для CartController::actionUpdate()
     */
    public static function updatePost()
    {
        try {
            $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
            
            if ($rawPurchasesModel->load(\Yii::$app->request->post()) && !empty(\Yii::$app->request->post('hash'))) {
                if ($rawPurchasesModel->validate()) {
                    $hash = \Yii::$app->request->post('hash');
                    if (array_key_exists($hash, (\Yii::$app->params['cartArray']))) {
                        unset(\Yii::$app->params['cartArray'][$hash]);
                        self::write($rawPurchasesModel->toArray());
                    }
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает POST запрос для CartController::actionDelete()
     */
    public static function deletePost()
    {
        try {
            if (!empty(\Yii::$app->request->post('hash'))) {
                $hash = \Yii::$app->request->post('hash');
                if (array_key_exists($hash, (\Yii::$app->params['cartArray']))) {
                    unset(\Yii::$app->params['cartArray'][$hash]);
                    if (empty(\Yii::$app->params['cartArray'])) {
                        self::cleanPost();
                    } else {
                        $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
                        SessionHelper::write($cartKey, \Yii::$app->params['cartArray']);
                    }
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет данными массив \Yii::$app->params['breadcrumbs'] 
     * - CartControllerHelper::indexGet
     */
    private static function breadcrumbs()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/cart/index'], 'label'=>\Yii::t('base', 'Cart')];
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Пишет в сессию массив данных о товарах в корзине
     * @param array $purchaseArray массив данных для записи в сессию
     * @return bool
     */
    private static function write(array $purchaseArray): bool
    {
        try {
            $clonePurchaseArray = $purchaseArray;
            unset($clonePurchaseArray['quantity']);
            $hash = HashHelper::createHash($clonePurchaseArray);
            \Yii::$app->params['cartArray'][$hash] = $purchaseArray;
            
            $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            SessionHelper::write($cartKey, \Yii::$app->params['cartArray']);
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
