<?php

namespace app\filters;

use yii\base\ActionFilter;

/**
 * Заполняет объект корзины данными сесии
 */
class ShoppingCartFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        $cartClass = \Yii::$app->cart;
        $session = \Yii::$app->session;
        if ($session->has('cart')) {
            $session->open();
            $cartClass::setProductsArray($session->get('cart'));
            $session->close();
        }
        $cartClass->getShortData();
        return parent::beforeAction($action);
    }
    
    public function afterAction($action, $result)
    {
        $cartClass = \Yii::$app->cart;
        if (!empty($cartClass::getProductsArray())) {
            $session = \Yii::$app->session;
            $session->open();
            $session->set('cart', $cartClass::getProductsArray());
            $session->close();
        }
        return parent::afterAction($action, $result);
    }
}
