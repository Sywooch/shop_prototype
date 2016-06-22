<?php

namespace app\filters;

use yii\base\ActionFilter;
use yii\helpers\Url;

/**
 * Заполняет объект корзины данными сесии
 */
class ProductsListFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        $session = \Yii::$app->session;
        if ($session->has(\Yii::$app->params['productsFiltersKeyInSession'])) {
            $session->open();
            \Yii::$app->params['productsFiltersArray'] = $session->get(\Yii::$app->params['productsFiltersKeyInSession']);
            $session->close();
        }
        return parent::beforeAction($action);
    }
    
    public function afterAction($action, $result)
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set(\Yii::$app->params['productsFiltersKeyInSession'], \Yii::$app->params['productsFiltersArray']);
        $session->close();
        return parent::afterAction($action, $result);
    }
}
