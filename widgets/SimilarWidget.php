<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\CurrencyModel;

/**
 * Выводит информацию о похожих товарах
 */
class SimilarWidget extends AbstractBaseWidget
{
    /**
     * @var array ProductsModel
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
            
            $renderArray['text'] = \Yii::t('base', 'Similar products');
            $renderArray['priceText'] = \Yii::t('base', 'Price:');
            
            foreach ($this->products as $product) {
                $set = [];
                $set['link'] = Html::a($product->name, Url::to(['/product-detail/index', \Yii::$app->params['productKey']=>$product->seocode]));
                
                if (!empty($product->images)) {
                    $imagesArray = glob(\Yii::getAlias('@imagesroot/' . $product->images) . '/thumbn_*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                    if (!empty($imagesArray)) {
                        $result = Html::img(\Yii::getAlias('@imagesweb/' . $product->images . '/') . basename($imagesArray[random_int(0, count($imagesArray) - 1)]), ['height'=>150]);
                    }
                    $set['image'] = !empty($result) ? $result : '';
                }
                
                $set['price'] = \Yii::$app->formatter->asDecimal($product->price * $this->currency->exchange_rate, 2) . ' ' . $this->currency->code;
                
                $renderArray['products'][] = $set;
            }
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array ProductsModel свойству ProductDetailWidget::products
     * @param array $products
     */
    public function setProducts(array $products)
    {
        try {
            $this->products = $products;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyModel свойству ProductDetailWidget::currency
     * @param CurrencyModel $model
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
