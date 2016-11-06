<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с данными о цене товара
 */
class TotalPriceWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var float цена товара
     */
    public $price;
    /**
     * @var int количество товара
     */
    public $quantity;
    
    /**
     * Форматирует стоимость с учетом текущей валюты
     * @return string
     */
    public function run(): string
    {
        try {
            if (!empty($this->price) && !empty($this->quantity) && !empty(\Yii::$app->currency->exchange_rate) && !empty(\Yii::$app->currency->code)) {
                $correctedTotalPrice = \Yii::$app->formatter->asDecimal(($this->price * $this->quantity) * \Yii::$app->currency->exchange_rate, 2) . ' ' . \Yii::$app->currency->code;
            }
            
            return $correctedTotalPrice ?? '';
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
