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
    
    public function init()
    {
        try {
            if (empty($this->price)) {
                throw new ErrorException(\Yii::t('base/errors', 'Not Evaluated {placeholder}!', ['placeholder'=>'$price']));
            }
            if (empty($this->quantity)) {
                throw new ErrorException(\Yii::t('base/errors', 'Not Evaluated {placeholder}!', ['placeholder'=>'$quantity']));
            }
            
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function run()
    {
        try {
            $correctedTotalPrice = \Yii::$app->formatter->asDecimal(($this->price * $this->quantity) * \Yii::$app->currency->exchange_rate, 2);
            return $correctedTotalPrice . ' ' . \Yii::$app->currency->code;
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
