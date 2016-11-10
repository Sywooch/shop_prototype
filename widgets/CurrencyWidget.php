<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с формой выбора валюты
 */
class CurrencyWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var string имя класса для построения запроса и создания экземпляра модели
     */
    public $modelClass;
    /**
     * @var string имя сценария модели
     */
    public $scenario;
    /**
     * @var array настройки форматирования результата запроса
     */
    public $format = [];
    /**
     * @var array настройки сортировки результата запроса
     */
    public $sorting = [];
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    /**
     * @var array массив валют, полученный из БД
     */
    private $_currencyArray = [];
    /**
     * @var object CurrencyWidget::modelClass
     */
    private $model;
    
    public function init()
    {
        try {
            $this->_currencyArray = $this->modelClass::find()->format($this->format)->all();
            
            $this->model = new $this->modelClass();
            $this->model->setScenario($this->scenario);
            
            if (!empty(\Yii::$app->currency)) {
                $this->model = \Yii::configure($this->model, \Yii::$app->currency->toArray());
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function run()
    {
        try {
            if (!empty($this->_currencyArray) && !empty($this->model)) {
                return $this->render($this->view, ['currency'=>$this->model, 'currencyList'=>$this->_currencyArray]);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
