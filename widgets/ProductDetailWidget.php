<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model,
    Widget};
use app\exceptions\ExceptionsTrait;
use yii\helpers\ArrayHelper;

class ProductDetailWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var Model
     */
    private $model;
    /**
     * @var object Widget
     */
    private $imagesWidget;
    /**
     * @var object Widget
     */
    private $priceWidget;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->model)) {
                throw new ErrorException($this->emptyError('model'));
            }
            if (empty($this->imagesWidget)) {
                throw new ErrorException($this->emptyError('imagesWidget'));
            }
            if (empty($this->priceWidget)) {
                throw new ErrorException($this->emptyError('priceWidget'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['name'] = $this->model->name;
            $renderArray['description'] = $this->model->description;
            
            if (!empty($this->model->images)) {
                $this->imagesWidget->path = $this->model->images;
                $renderArray['images'] = $this->imagesWidget->run();
            }
            
            $colors = $this->model->colors;
            ArrayHelper::multisort($colors, 'color');
            $renderArray['colors'] = ArrayHelper::getColumn($colors, 'color');
            
            $sizes = $this->model->sizes;
            ArrayHelper::multisort($sizes, 'size');
            $renderArray['sizes'] = ArrayHelper::getColumn($sizes, 'size');
            
            $this->priceWidget->price = $this->model->price;
            $renderArray['price'] = $this->priceWidget->run();
            
            $renderArray['code'] = $this->model->code;
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model свойству ProductDetailWidget::model
     * @param object $model Model
     */
    public function setModel(Model $model)
    {
        try {
            $this->model = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Widget свойству ProductDetailWidget::imagesWidget
     * @param Widget $widget
     */
    public function setImagesWidget(Widget $widget)
    {
        try {
            $this->imagesWidget = $widget;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Widget свойству ProductDetailWidget::priceWidget
     * @param Widget $widget
     */
    public function setPriceWidget(Widget $widget)
    {
        try {
            $this->priceWidget = $widget;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
