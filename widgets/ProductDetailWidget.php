<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model,
    Widget};
use app\exceptions\ExceptionsTrait;
use yii\helpers\{ArrayHelper,
    Html};
use app\models\{CurrencyModel,
    ProductsModel};

class ProductDetailWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var ProductsModel
     */
    private $product;
    /**
     * @var object Widget
     */
    //private $imagesWidget;
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
            /*if (empty($this->imagesWidget)) {
                throw new ErrorException($this->emptyError('imagesWidget'));
            }*/
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
                /*$this->imagesWidget->path = $this->model->images;
                $renderArray['images'] = $this->imagesWidget->run();*/
                
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
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model свойству ProductDetailWidget::model
     * @param object $model Model
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
     * Присваивает Widget свойству ProductDetailWidget::imagesWidget
     * @param Widget $widget
     */
    /*public function setImagesWidget(Widget $widget)
    {
        try {
            $this->imagesWidget = $widget;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }*/
    
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
