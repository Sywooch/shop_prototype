<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Html;
use app\widgets\AbstractBaseWidget;
use app\collections\ProductsCollection;
use app\models\CurrencyModel;

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class ProductsWidget extends AbstractBaseWidget
{
    /**
     * @var object ProductsCollection
     */
    private $products;
    /**
     * @var CurrencyModel
     */
    private $currency;
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
            if (empty($this->products)) {
                throw new ErrorException($this->emptyError('products'));
            }
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            foreach ($this->products as $product) {
                $set = [];
                $set['id'] = $product->id;
                $set['link'] = Html::a(Html::encode($product->name), ['/product-detail/index', 'seocode'=>$product->seocode]);
                $set['short_description'] = $product->short_description;
                $set['price'] = \Yii::$app->formatter->asDecimal($product->price * $this->currency->exchange_rate, 2) . ' ' . $this->currency->code;
                
                if (!empty($product->images)) {
                    $imagesArray = glob(\Yii::getAlias('@imagesroot/' . $product->images) . '/thumbn_*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                    if (!empty($imagesArray)) {
                        $set['image'] = Html::img(\Yii::getAlias('@imagesweb/' . $product->images . '/') . basename($imagesArray[random_int(0, count($imagesArray) - 1)]));
                    }
                }
                
                $renderArray['collection'][] = $set;
            }
            
            $renderArray['priceText'] = \Yii::t('base', 'Price');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsCollection свойству ProductsWidget::products
     * @param ProductsCollection $products
     */
    public function setProducts(ProductsCollection $products)
    {
        try {
            $this->products = $products;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyModel свойству ProductsWidget::currency
     * @param CurrencyModel $currency
     */
    public function setCurrency(CurrencyModel $currency)
    {
        try {
            $this->currency = $currency;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
