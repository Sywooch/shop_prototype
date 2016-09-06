<?php

namespace app\filters;

use yii\base\{ActionFilter, 
    ErrorException};
use yii\helpers\Url;
use app\traits\ExceptionsTrait;

/**
 * Заполняет объект корзины данными сесии
 */
class AbstractFilter extends ActionFilter
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
            
            $key = md5(implode('_', [
                !empty(\Yii::$app->request->get(\Yii::$app->params['categoriesKey'])) ? \Yii::$app->request->get(\Yii::$app->params['categoriesKey']) : '', 
                !empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) ? \Yii::$app->request->get(\Yii::$app->params['subcategoryKey']) : '', 
                !empty(\Yii::$app->request->get(\Yii::$app->params['searchKey'])) ? \Yii::$app->request->get(\Yii::$app->params['searchKey']) : ''
            ]));
            
            $session = \Yii::$app->session;
            if ($session->has($key)) {
                $session->open();
                $attributes = $session->get($key);
                if (!is_array($attributes) || empty($attributes)) {
                    throw new ErrorException('Ошибка при получении данных из сессии!');
                }
                $session->close();
                return $attributes;
            }
            return false;
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
            
            $key = md5(implode('_', [
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
