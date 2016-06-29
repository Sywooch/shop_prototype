<?php

namespace app\filters;

use yii\base\ActionFilter;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;

/**
 * Заполняет объект корзины данными сесии
 */
class ProductsListFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * Восстанавливает из сессионного хранилища объект, хранящий фильтры, отфильтровывающие выдачу списка товаров
     * @param $action выполняемое в данный момент действие
     * @return parent result
     */
    public function beforeAction($action)
    {
        try {
            if (empty(\Yii::$app->params['filtersKeyInSession'])) {
                throw new ErrorException('Не установлена переменная filtersKeyInSession!');
            }
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не установлена переменная categoryKey!');
            }
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не установлена переменная subCategoryKey!');
            }
            if (empty(\Yii::$app->params['searchKey'])) {
                throw new ErrorException('Не установлена переменная searchKey!');
            }
            $session = \Yii::$app->session;
            if ($session->has(\Yii::$app->params['filtersKeyInSession'])) {
                $session->open();
                
                $attributes = $session->get(\Yii::$app->params['filtersKeyInSession']);
                if (!is_array($attributes) || empty($attributes)) {
                    throw new ErrorException('Ошибка при получении данных из сессии!');
                }
                
                if ($attributes[\Yii::$app->params['categoryKey']] == \Yii::$app->request->get(\Yii::$app->params['categoryKey']) && $attributes[\Yii::$app->params['subCategoryKey']] == \Yii::$app->request->get(\Yii::$app->params['subCategoryKey']) && $attributes[\Yii::$app->params['searchKey']] == \Yii::$app->request->get(\Yii::$app->params['searchKey'])) {
                    \Yii::$app->filters->attributes = $attributes;
                }
                
                $session->close();
            }
            return parent::beforeAction($action);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Сохраняет значение свойств-фильтров в сесии
     * @param $action выполняемое в данный момент действие
     * @param $result результирующая строка перед отправкой в браузер клиента
     * @return parent result
     */
    public function afterAction($action, $result)
    {
        try {
            if (empty(\Yii::$app->params['filtersKeyInSession'])) {
                throw new ErrorException('Не установлена переменная filtersKeyInSession!');
            }
            $session = \Yii::$app->session;
            $session->open();
            $session->set(\Yii::$app->params['filtersKeyInSession'], \Yii::$app->filters->attributes);
            $session->close();
            return parent::afterAction($action, $result);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
