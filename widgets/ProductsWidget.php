<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Html;
use app\widgets\AbstractBaseWidget;
use app\collections\CollectionInterface;
use app\models\CurrencyInterface;
use app\helpers\ImgHelper;

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class ProductsWidget extends AbstractBaseWidget
{
    /**
     * @var object CollectionInterface
     */
    private $products;
    /**
     * @var CurrencyInterface
     */
    private $currency;
    /**
     * @var string имя шаблона
     */
    private $template;
    
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
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            foreach ($this->products as $product) {
                $set = [];
                $set['id'] = $product->id;
                $set['link'] = Html::a(Html::encode($product->name), ['/product-detail/index', 'seocode'=>$product->seocode]);
                $set['short_description'] = $product->short_description;
                $set['price'] = sprintf('%s %s', \Yii::$app->formatter->asDecimal($product->price * $this->currency->exchangeRate(), 2), $this->currency->code());
                
                if (!empty($product->images)) {
                    $set['image'] = ImgHelper::randThumbn($product->images);
                }
                
                $renderArray['collection'][] = $set;
            }
            
            $renderArray['priceText'] = \Yii::t('base', 'Price');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству ProductsWidget::products
     * @param CollectionInterface $products
     */
    public function setProducts(CollectionInterface $products)
    {
        try {
            $this->products = $products;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyInterface свойству ProductsWidget::currency
     * @param CurrencyInterface $currency
     */
    public function setCurrency(CurrencyInterface $currency)
    {
        try {
            $this->currency = $currency;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству ProductsWidget::template
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        try {
            $this->template = $template;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
