<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с формой выбора валюты
 */
class CurrencyWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var string имя класса для построения запроса
     */
    public $modelClass;
    /**
     * @var object ActiveRecord, получает и хранит выбранную валюту
     */
    public $model;
    /**
     * @var string имя сценария модели
     */
    public $scenario;
    /**
     * @var array настройки пост форматирования результата запроса
     */
    public $postFormatting = [];
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            $currencyArray = $this->modelClass::find()->all();
            $currencyArray = ArrayHelper::map($currencyArray, $this->postFormatting['key'], $this->postFormatting['value']);
            asort($currencyArray, SORT_STRING);
            
            $this->model = new $this->modelClass();
            $this->model->setScenario($this->scenario);
            
            if (!empty(\Yii::$app->currency)) {
                $this->model = \Yii::configure($this->model, \Yii::$app->currency->toArray());
            }
            
            if (!empty($currencyArray) && !empty($this->model)) {
                return $this->render($this->view, ['currency'=>$this->model, 'currencyList'=>$currencyArray]);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
