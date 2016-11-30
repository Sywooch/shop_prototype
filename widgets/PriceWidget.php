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
    private $currencyModel;
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
            if (empty($this->currencyModel)) {
                throw new ErrorException(ExceptionsTrait::emptyError('currencyModel'));
            }
            if (!isset($this->price)) {
                throw new ErrorException(ExceptionsTrait::emptyError('price'));
            }
            
            $correctedPrice = \Yii::$app->formatter->asDecimal($this->price * $this->currencyModel->exchange_rate, 2) . ' ' . $this->currencyModel->code;
            
            return $correctedPrice ?? '';
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model свойству PriceWidget::currencyModel
     * @param object $model Model
     */
    public function setCurrencyModel(Model $model)
    {
        try {
            $this->currencyModel = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
