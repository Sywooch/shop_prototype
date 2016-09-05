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
            if (empty(\Yii::$app->params['categoriesKey'])) {
                throw new ErrorException('Не установлена переменная categoriesKey!');
            }
            if (empty(\Yii::$app->params['subcategoryKey'])) {
                throw new ErrorException('Не установлена переменная subcategoryKey!');
            }
            if (empty(\Yii::$app->params['searchKey'])) {
                throw new ErrorException('Не установлена переменная searchKey!');
            }
            
            if (!empty($attributes = parent::before())) {
                if ($attributes[\Yii::$app->params['categoriesKey']] == \Yii::$app->request->get(\Yii::$app->params['categoriesKey']) && $attributes[\Yii::$app->params['subcategoryKey']] == \Yii::$app->request->get(\Yii::$app->params['subcategoryKey']) && $attributes[\Yii::$app->params['searchKey']] == \Yii::$app->request->get(\Yii::$app->params['searchKey'])) {
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
