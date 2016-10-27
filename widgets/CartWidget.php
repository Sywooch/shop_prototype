<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\{Html,
    Url};
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class CartWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var int количество товаров в корзине
     */
    private $_productsCount = 0;
    /**
     * @var float общая стоимость товаров в корзине
     */
    private $_totalCost = 0.00;
    /**
     * @var string результирубщая HTML строка
     */
    private $_html = '';
    
    /**
     * Конструирует HTML строку с информацией о текущем статусе корзины заказов
     * @return string
     */
    public function run()
    {
        try {
            if (!empty(\Yii::$app->params['cartArray'])) {
                foreach (\Yii::$app->params['cartArray'] as $purchase) {
                    ++$this->_productsCount;
                    $this->_totalCost += $purchase['price'];
                }
                
                $correctedTotalCost = \Yii::$app->formatter->asDecimal($this->_totalCost * \Yii::$app->currency->exchange_rate, 2) . ' ' . \Yii::$app->currency->code;
                
                $this->_html = Html::tag('p', \Yii::t('base', 'Products in cart: {productsCount}, Total cost: {totalCost}', ['productsCount'=>$this->_productsCount, 'totalCost'=>$correctedTotalCost]));
            }
            
            return $this->_html;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
