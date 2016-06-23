<?php

namespace app\filters;

use yii\base\ActionFilter;

/**
 * Заполняет объект корзины данными сесии
 */
class ProductsListFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        $session = \Yii::$app->session;
        if ($session->has(\Yii::$app->params['filtersKeyInSession'])) {
            $session->open();
            
            $attributes = $session->get(\Yii::$app->params['filtersKeyInSession']);
            if ($attributes[\Yii::$app->params['categoryKey']] == \Yii::$app->request->get(\Yii::$app->params['categoryKey']) && $attributes[\Yii::$app->params['subCategoryKey']] == \Yii::$app->request->get(\Yii::$app->params['subCategoryKey']) && $attributes[\Yii::$app->params['searchKey']] == \Yii::$app->request->get(\Yii::$app->params['searchKey'])) {
                \Yii::$app->filters->attributes = $attributes;
            }
            
            $session->close();
        }
        return parent::beforeAction($action);
    }
    
    public function afterAction($action, $result)
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set(\Yii::$app->params['filtersKeyInSession'], \Yii::$app->filters->attributes);
        $session->close();
        return parent::afterAction($action, $result);
    }
}
