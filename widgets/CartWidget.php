<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class CartWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var bool нужно ли добавлять ссылку на корзину
     */
    public $toCart = true;
    /**
     * @var int количество товаров в корзине
     */
    private $_productsCount = 0;
    /**
     * @var float общая стоимость товаров в корзине
     */
    private $_totalCost = 0;
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
                $productsQuery = ProductsModel::find();
                $productsQuery->extendSelect(['id', 'price']);
                $productsQuery->where(['[[products.id]]'=>ArrayHelper::getColumn(\Yii::$app->params['cartArray'], 'id_product')]);
                $productsArray = $productsQuery->allArray();
                /*if (!is_array($productsArray) || empty($productsArray)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $productsArray']));
                }*/
                $cartArrayMap = ArrayHelper::map(\Yii::$app->params['cartArray'], 'id_product', 'quantity');
                foreach ($productsArray as $product) {
                    $this->_productsCount += $cartArrayMap[$product['id']];
                    $this->_totalCost += ($product['price'] * $cartArrayMap[$product['id']]);
                }
                
                $toCart = Html::a(\Yii::t('base', 'To cart'), Url::to(['/cart/index']));
            }
            
            if (!empty(\Yii::$app->currency->exchange_rate) && !empty(\Yii::$app->currency->code)) {
                $this->_totalCost = \Yii::$app->formatter->asDecimal($this->_totalCost * \Yii::$app->currency->exchange_rate, 2) . ' ' . \Yii::$app->currency->code;
            }
            
            $text = \Yii::t('base', 'Products in cart: {productsCount}, Total cost: {totalCost}', ['productsCount'=>$this->_productsCount, 'totalCost'=>$this->_totalCost]);
            $text .= ($this->toCart && isset($toCart)) ? ' ' . $toCart : '';
            $this->_result[] = Html::tag('p', $text);
            
            $form = Html::beginForm(Url::to(['/cart/clean']), 'POST', ['id'=>'clean-cart-form']);
            $form .= Html::submitButton(\Yii::t('base', 'Clean'), !isset($toCart) ? ['disabled'=>true] : []);
            $form .= Html::endForm();
            $this->_result[] = $form;
            
            return !empty($this->_result) ? Html::tag('div', implode('', $this->_result), ['id'=>'cart']) : '';
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
