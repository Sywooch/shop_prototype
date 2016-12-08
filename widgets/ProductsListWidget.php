<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\Html;
use app\exceptions\ExceptionsTrait;
use app\collections\CollectionInterface;

/**
 * Формирует HTML строку, представляющую каталог товаров
 */
class ProductsListWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object CollectionInterface
     */
    private $productsCollection;
    /**
     * @var object Widget
     */
    private $priceWidget;
    /**
     * @var object Widget
     */
    private $thumbnailsWidget;
    /**
     * @var object Widget
     */
    private $paginationWidget;
        /**
     * @var string имя шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->productsCollection)) {
                throw new ErrorException($this->emptyError('productsCollection'));
            }
            if (empty($this->priceWidget)) {
                throw new ErrorException($this->emptyError('priceWidget'));
            }
            if (empty($this->thumbnailsWidget)) {
                throw new ErrorException($this->emptyError('thumbnailsWidget'));
            }
            if (empty($this->paginationWidget)) {
                throw new ErrorException($this->emptyError('paginationWidget'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $dataArray = [];
            
            foreach ($this->productsCollection as $product) {
                $set = [];
                $set['link'] = Html::a($product->name, ['product-detail/index', 'seocode'=>$product->seocode]);
                $set['short_description'] = $product->short_description;
                
                $this->priceWidget->price = $product->price;
                $set['price'] = $this->priceWidget->run();
                
                if (!empty($product->images)) {
                    $this->thumbnailsWidget->path = $product->images;
                    $set['images'] = $this->thumbnailsWidget->run();
                }
                $dataArray['collection'][] = $set;
            }
            
            $this->paginationWidget->pagination = $this->productsCollection->pagination;
            $dataArray['pagination'] = $this->paginationWidget->run();
            
            return $this->render($this->view, $dataArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству ProductsListWidget::productsCollection
     * @param object $collection CollectionInterface
     */
    public function setProductsCollection(CollectionInterface $collection)
    {
        try {
            $this->productsCollection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Widget свойству ProductsListWidget::priceWidget
     * @param object $widget Widget
     */
    public function setPriceWidget(Widget $widget)
    {
        try {
            $this->priceWidget = $widget;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Widget свойству ProductsListWidget::thumbnailsWidget
     * @param object $widget Widget
     */
    public function setThumbnailsWidget(Widget $widget)
    {
        try {
            $this->thumbnailsWidget = $widget;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Widget свойству ProductsListWidget::paginationWidget
     * @param object $widget Widget
     */
    public function setPaginationWidget(Widget $widget)
    {
        try {
            $this->paginationWidget = $widget;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
