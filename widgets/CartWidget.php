<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class CartWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку с информацией о текущем статусе корзины заказов
     * @return string
     */
    public function run()
    {
        try {
            $goods = \Yii::$app->cart->goods;
            $totalCost = \Yii::$app->cart->totalCost;
            
            if (!empty(\Yii::$app->currency->exchange_rate) && !empty(\Yii::$app->currency->code)) {
                $totalCost = \Yii::$app->formatter->asDecimal($totalCost * \Yii::$app->currency->exchange_rate, 2) . ' ' . \Yii::$app->currency->code;
            }
            
            return $this->render($this->view, ['goods'=>$goods, 'totalCost'=>$totalCost]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
