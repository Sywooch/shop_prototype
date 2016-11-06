<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с данными о цене товара
 */
class PriceWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var float цена товара
     */
    public $price;
    
    /**
     * Форматирует стоимость с учетом текущей валюты
     * @return string
     */
    public function run(): string
    {
        try {
            if (!empty($this->price) && !empty(\Yii::$app->currency->exchange_rate) && !empty(\Yii::$app->currency->code)) {
                $correctedPrice = \Yii::$app->formatter->asDecimal($this->price * \Yii::$app->currency->exchange_rate, 2) . ' ' . \Yii::$app->currency->code;
            }
            
            return $correctedPrice ?? '';
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
