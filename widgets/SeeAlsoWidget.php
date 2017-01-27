<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\CurrencyInterface;

/**
 * Выводит информацию о похожих товарах
 */
class SeeAlsoWidget extends AbstractBaseWidget
{
    /**
     * @var array ProductsModel
     */
    private $products;
    /**
     * @var CurrencyInterface
     */
    private $currency;
    /**
     * @var string заголовок
     */
    private $header;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    public function run()
    {
        try {
            if (empty($this->products)) {
                return '';
            }
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            $renderArray['priceText'] = \Yii::t('base', 'Price');
            
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
                
                $set['price'] = \Yii::$app->formatter->asDecimal($product->price * $this->currency->exchangeRate(), 2) . ' ' . $this->currency->code();
                
                $renderArray['products'][] = $set;
            }
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array ProductsModel свойству SeeAlsoWidget::products
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
     * Присваивает CurrencyInterface свойству SeeAlsoWidget::currency
     * @param CurrencyInterface $model
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
     * Присваивает заголовок свойству SeeAlsoWidget::header
     * @param string $header
     */
    public function setHeader(string $header)
    {
        try {
            $this->header = $header;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству SeeAlsoWidget::template
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
