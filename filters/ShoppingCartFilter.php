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
        \Yii::$app->cart->getShortData();
        return parent::beforeAction($action);
    }
}
