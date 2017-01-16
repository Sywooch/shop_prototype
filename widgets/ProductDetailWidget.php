<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\AbstractBaseWidget;
use yii\helpers\{ArrayHelper,
    Html};
use app\models\{CurrencyModel,
    ProductsModel};

/**
 * Выводит информацию о товаре
 */
class ProductDetailWidget extends AbstractBaseWidget
{
    /**
     * @var ProductsModel
     */
    private $product;
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
            if (empty($this->product)) {
                throw new ErrorException($this->emptyError('product'));
            }
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['name'] = $this->product->name;
            $renderArray['description'] = $this->product->description;
            
            if (!empty($this->product->images)) {
                $imagesArray = glob(\Yii::getAlias('@imagesroot/' . $this->product->images) . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                if (!empty($imagesArray)) {
                    $result = [];
                    foreach ($imagesArray as $image) {
                        if (preg_match('/^(?!thumbn_).+$/', basename($image)) === 1) {
                            $result[] = Html::img(\Yii::getAlias('@imagesweb/' . $this->product->images . '/') . basename($image));
                        }
                    }
                }
                $renderArray['images'] = !empty($result) ? implode('<br/>', $result) : '';
            }
            
            $colors = $this->product->colors;
            ArrayHelper::multisort($colors, 'color');
            $renderArray['colors'] = ArrayHelper::getColumn($colors, 'color');
            
            $sizes = $this->product->sizes;
            ArrayHelper::multisort($sizes, 'size');
            $renderArray['sizes'] = ArrayHelper::getColumn($sizes, 'size');
            
            $renderArray['price'] = \Yii::$app->formatter->asDecimal($this->product->price * $this->currency->exchange_rate, 2) . ' ' . $this->currency->code;
            $renderArray['code'] = $this->product->code;
            $renderArray['colorsText'] = \Yii::t('base', 'Colors');
            $renderArray['sizesText'] = \Yii::t('base', 'Sizes');
            $renderArray['priceText'] = \Yii::t('base', 'Price');
            $renderArray['codeText'] = \Yii::t('base', 'Code');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsModel свойству ProductDetailWidget::product
     * @param ProductsModel $product
     */
    public function setProduct(ProductsModel $product)
    {
        try {
            $this->product = $product;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyModel свойству ProductDetailWidget::currency
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
