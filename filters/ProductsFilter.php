<?php

namespace app\filters;

use yii\base\{ActionFilter, 
    ErrorException};
use yii\helpers\Url;

/**
 * Заполняет объект корзины данными сесии
 */
class ProductsFilter extends ActionFilter
{
    /**
     * Конфигурирует \Yii::$app->filters данными из сессионного хранилища
     * @param $action выполняемое в данный момент действие
     * @return parent result
     */
    public function beforeAction($action)
    {
        try {
            if (empty(\Yii::$app->params['filtersKeyInSession'])) {
                throw new ErrorException('Не установлена переменная filtersKeyInSession!');
            }
            if (empty(\Yii::$app->params['categoriesKey'])) {
                throw new ErrorException('Не установлена переменная categoriesKey!');
            }
            if (empty(\Yii::$app->params['subcategoryKey'])) {
                throw new ErrorException('Не установлена переменная subcategoryKey!');
            }
            if (empty(\Yii::$app->params['searchKey'])) {
                throw new ErrorException('Не установлена переменная searchKey!');
            }
            
            $key = \Yii::$app->params['filtersKeyInSession'] . '.' . md5(implode('_', [
                !empty(\Yii::$app->request->get(\Yii::$app->params['categoriesKey'])) ? \Yii::$app->request->get(\Yii::$app->params['categoriesKey']) : '', 
                !empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) ? \Yii::$app->request->get(\Yii::$app->params['subcategoryKey']) : '', 
                !empty(\Yii::$app->request->get(\Yii::$app->params['searchKey'])) ? \Yii::$app->request->get(\Yii::$app->params['searchKey']) : ''
            ]));
            
            $session = \Yii::$app->session;
            
            if ($session->has($key)) {
                $session->open();
                $attributes = $session->get($key);
                $session->close();
                
                if (!is_array($attributes) || empty($attributes)) {
                    throw new ErrorException('Ошибка при получении данных из сессии!');
                }
                
                \Yii::configure(\Yii::$app->filters, $attributes);
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
            
            $key = \Yii::$app->params['filtersKeyInSession'] . '.' . md5(implode('_', [
                !empty(\Yii::$app->filters->categories) ? \Yii::$app->filters->categories : '', 
                !empty(\Yii::$app->filters->subcategory) ? \Yii::$app->filters->subcategory : '', 
                !empty(\Yii::$app->filters->search) ? \Yii::$app->filters->search : ''
            ]));
            
            $session = \Yii::$app->session;
            $session->open();
            $session->set($key, \Yii::$app->filters->attributes);
            $session->close();
            return parent::afterAction($action, $result);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
