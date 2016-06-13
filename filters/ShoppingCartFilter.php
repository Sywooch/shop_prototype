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
        if ($session->has(\Yii::$app->params['cartKeyInSession'])) {
            $session->open();
            $cartClass::setProductsArray($session->get(\Yii::$app->params['cartKeyInSession']));
            $session->close();
            $cartClass::getShortData();
        }
        return parent::beforeAction($action);
    }
    
    public function afterAction($action, $result)
    {
        $cartClass = \Yii::$app->cart;
        $session = \Yii::$app->session;
        $session->open();
        $session->set(\Yii::$app->params['cartKeyInSession'], $cartClass::getProductsArray());
        $session->close();
        return parent::afterAction($action, $result);
    }
}
