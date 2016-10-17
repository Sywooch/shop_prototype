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
    
    public function init()
    {
        try {
            if (empty($this->price)) {
                throw new ErrorException(\Yii::t('base/errors', 'Not Evaluated {placeholder}!', ['placeholder'=>'$price']));
            }
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function run()
    {
        try {
            $correctPrice = \Yii::$app->formatter->asDecimal($this->price * \Yii::$app->currency->exchange_rate, 2);
            return '<p><strong>' . \Yii::t('base', 'Price:') . '</strong> ' . $correctPrice . ' ' . \Yii::$app->currency->code . '</p>';
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
