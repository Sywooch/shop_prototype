<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\controllers\{AbstractBaseController,
    CartControllerHelper};
use app\helpers\UrlHelper;

/**
 * Обрабатывает запросы, связанные с данными корзины
 */
class CartController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос детальной информации о товарах в корзине
     */
    public function actionIndex()
    {
        try {
            if (empty(\Yii::$app->params['cartArray'])) {
                return $this->redirect(Url::to(['/products-list/index']));
            }
            
            $renderArray = CartControllerHelper::indexGet();
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('cart.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на добавление товара в корзину
     */
    public function actionSet()
    {
        try {
            /*if (\Yii::$app->request->isAjax) {
                return CartControllerHelper::setAjax();
            }*/
            if (\Yii::$app->request->isPost) {
                CartControllerHelper::setPost();
            }
            
            return $this->redirect(\yii\helpers\Url::previous(\Yii::$app->id));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на удаление всех товаров из корзины
     */
    public function actionClean()
    {
        try {
            if (\Yii::$app->request->isPost) {
                CartControllerHelper::cleanPost();
            }
            
            return $this->redirect(UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на обновление характеристик товара в корзине
     */
    public function actionUpdate()
    {
        try {
            if (\Yii::$app->request->isPost) {
                CartControllerHelper::updatePost();
            }
            
            return $this->redirect(UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
     /**
     * Обрабатывает запрос на удаление 1 товара из корзины
     */
    public function actionDelete()
    {
        try {
            if (\Yii::$app->request->isPost) {
                CartControllerHelper::deletePost();
            }
            
            return $this->redirect(UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на добавление данных о покупателе 
     */
    public function actionCustomer()
    {
        try {
            if (empty(\Yii::$app->params['cartArray'])) {
                return $this->redirect(Url::to(['/products-list/index']));
            }
            
            if (\Yii::$app->request->isPost) {
                if (CartControllerHelper::customerPost()) {
                    return $this->redirect(Url::to(['/cart/check']));
                }
            }
            
            $renderArray = CartControllerHelper::customerGet();
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('customer.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос к странице проверки данных покупки
     * @return string
     */
    public function actionCheck()
    {
        try {
            if (empty(\Yii::$app->params['cartArray']) || empty(\Yii::$app->params['customerArray'])) {
                return $this->redirect(Url::to(['/products-list/index']));
            }
            
            $renderArray = CartControllerHelper::checkGet();
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('check.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает подтвержденный заказ
     * @return string
     */
    public function actionSend()
    {
        try {
            if (!\Yii::$app->request->isPost || empty(\Yii::$app->params['cartArray']) || empty(\Yii::$app->params['customerArray'])) {
                return $this->redirect(Url::to(['/products-list/index']));
            }
            
            CartControllerHelper::sendPost();
            
            return $this->redirect(Url::to(['/cart/complete']));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Подтверждает успешную отправку
     * @return string
     */
    public function actionComplete()
    {
        try {
            $renderArray = CartControllerHelper::completeGet();
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('complete.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\CurrencyFilter',
                'only'=>['index', 'set', 'customer', 'check', 'send', 'complete']
            ],
            [
                'class'=>'app\filters\CustomerFilter',
                'only'=>['customer', 'check', 'send']
            ],
            [
                'class'=>'app\filters\CartFilter',
            ],
        ];
    }
}
