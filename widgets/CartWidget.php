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
     * @var array массив результирующих строк
     */
    private $_result = [];
    
    /**
     * Конструирует HTML строку с информацией о текущем статусе корзины заказов
     * @return string
     */
    public function run()
    {
        try {
            if (!empty(\Yii::$app->params['cartArray'])) {
                foreach (\Yii::$app->params['cartArray'] as $purchase) {
                    $this->_productsCount += $purchase['quantity'];
                    $this->_totalCost += ($purchase['price'] * $purchase['quantity']);
                }
                
                if (!empty(\Yii::$app->currency->exchange_rate) && !empty(\Yii::$app->currency->code)) {
                    $this->_totalCost = \Yii::$app->formatter->asDecimal($this->_totalCost * \Yii::$app->currency->exchange_rate, 2) . ' ' . \Yii::$app->currency->code;
                }
                
                $this->_result[] = Html::tag('p', \Yii::t('base', 'Products in cart: {productsCount}, Total cost: {totalCost}', ['productsCount'=>$this->_productsCount, 'totalCost'=>$this->_totalCost]) . ' ' . Html::a(\Yii::t('base', 'Shopping details'), ''));
                
                $form = Html::beginForm(Url::to(['/cart/clean']), 'POST', ['id'=>'clean-cart-form']);
                $form .= Html::submitButton(\Yii::t('base', 'Clean'));
                $form .= Html::endForm();
                $this->_result[] = $form;
            }
            
            return implode('', $this->_result);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
