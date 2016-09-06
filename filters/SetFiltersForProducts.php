<?php

namespace app\filters;

use yii\base\{ActionFilter, 
    ErrorException};
use app\helpers\HashHelper;

/**
 * Заполняет объект корзины данными сессии
 */
class SetFiltersForProducts extends ActionFilter
{
    /**
     * Сохраняет значение свойств-фильтров в сессии
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
            
            $key = \Yii::$app->params['filtersKeyInSession'] . '.' . HashHelper::createHashFromFilters();
            
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
