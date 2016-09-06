<?php

namespace app\filters;

use yii\base\{ActionFilter, 
    ErrorException};
use app\helpers\HashHelper;

/**
 * Заполняет объект корзины данными сессии
 */
class GetFiltersForProducts extends ActionFilter
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
            
            $key = \Yii::$app->params['filtersKeyInSession'] . '.' . HashHelper::createHashFromGet();
            
            $session = \Yii::$app->session;
            
            if ($session->has($key)) {
                $session->open();
                $attributes = $session->get($key);
                $session->close();
                
                if (is_array($attributes) && !empty($attributes)) {
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
