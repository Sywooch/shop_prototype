<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model,
    Widget};
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с данными о цене товара
 */
class PriceWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object Model
     */
    private $model;
    /**
     * @var float цена товара
     */
    public $price;
    
    /**
     * Форматирует стоимость с учетом текущей валюты
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->model)) {
                throw new ErrorException($this->emptyError('model'));
            }
            if (!isset($this->price)) {
                throw new ErrorException($this->emptyError('price'));
            }
            
            $correctedPrice = \Yii::$app->formatter->asDecimal($this->price * $this->model->exchange_rate, 2) . ' ' . $this->model->code;
            
            return $correctedPrice ?? '';
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model свойству PriceWidget::model
     * @param object $model Model
     */
    public function setModel(Model $model)
    {
        try {
            $this->model = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
