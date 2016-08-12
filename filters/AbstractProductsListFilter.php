<?php

namespace app\filters;

use yii\base\{ActionFilter, 
    ErrorException};
use app\traits\ExceptionsTrait;

/**
 * Заполняет объект корзины данными сесии
 */
class AbstractProductsListFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * @var string имя переменной, хранящей данные фильтров в сесии
     */
    protected $_filtersKeyInSession;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty(\Yii::$app->params['filtersKeyInSession'])) {
                throw new ErrorException('Не установлена переменная filtersKeyInSession!');
            }
            
            $this->_filtersKeyInSession = \Yii::$app->params['filtersKeyInSession'];
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Получает из сессионного хранилища данные фильтров для списка товаров
     * @return array
     */
    protected function before()
    {
        try {
            if (empty($this->_filtersKeyInSession)) {
                throw new ErrorException('Не установлена переменная filtersKeyInSession!');
            }
            
            $session = \Yii::$app->session;
            if ($session->has($this->_filtersKeyInSession)) {
                $session->open();
                $attributes = $session->get($this->_filtersKeyInSession);
                if (!is_array($attributes) || empty($attributes)) {
                    throw new ErrorException('Ошибка при получении данных из сессии!');
                }
                $session->close();
                return $attributes;
            }
            return null;
        } catch (\Exception $e) {
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
            if (empty($this->_filtersKeyInSession)) {
                throw new ErrorException('Не установлена переменная filtersKeyInSession!');
            }
            
            $session = \Yii::$app->session;
            $session->open();
            $session->set($this->_filtersKeyInSession, \Yii::$app->filters->attributes);
            $session->close();
            return parent::afterAction($action, $result);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
