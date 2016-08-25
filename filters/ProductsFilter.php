<?php

namespace app\filters;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\filters\AbstractFilter;

/**
 * Заполняет объект корзины данными сесии
 */
class ProductsFilter extends AbstractFilter
{
    /**
     * Конфигурирует \Yii::$app->filters данными из сессионного хранилища
     * @param $action выполняемое в данный момент действие
     * @return parent result
     */
    public function beforeAction($action)
    {
        try {
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не установлена переменная categoryKey!');
            }
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не установлена переменная subCategoryKey!');
            }
            if (empty(\Yii::$app->params['searchKey'])) {
                throw new ErrorException('Не установлена переменная searchKey!');
            }
            
            if (!empty($attributes = parent::before())) {
                if ($attributes[\Yii::$app->params['categoryKey']] == \Yii::$app->request->get(\Yii::$app->params['categoryKey']) && $attributes[\Yii::$app->params['subCategoryKey']] == \Yii::$app->request->get(\Yii::$app->params['subCategoryKey']) && $attributes[\Yii::$app->params['searchKey']] == \Yii::$app->request->get(\Yii::$app->params['searchKey'])) {
                    \Yii::configure(\Yii::$app->filters, $attributes);
                }
            }
            
            return parent::beforeAction($action);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
