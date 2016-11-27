<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model,
    Widget};
use yii\helpers\Html;
use app\exceptions\ExceptionsTrait;
use app\models\{CollectionInterface,
    CurrencyModel};
use app\widgets\{PaginationWidget,
    PriceWidget,
    ThumbnailsWidget};
use app\repositories\SessionRepository;

class ProductsListWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object Model
     */
    private $searchModel;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->searchModel)) {
                throw new ErrorException(ExceptionsTrait::emptyError('searchModel'));
            }
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('view'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function run()
    {
        try {
            $productsCollection = $this->searchModel->search();
            
            $renderArray = [];
            
            $collection = [];
            foreach ($productsCollection as $product) {
                $set = [];
                $set['link'] = Html::a($product->name, ['product-detail/index', 'seocode'=>$product->seocode]);
                $set['short_description'] = $product->short_description;
                $set['price'] = PriceWidget::widget([
                    'repository'=>new SessionRepository([
                        'class'=>CurrencyModel::class
                    ]), 
                    'price'=>$product->price
                ]);
                if (!empty($product->images)) {
                    $set['images'] = ThumbnailsWidget::widget([
                        'path'=>$product->images, 
                        'view'=>'thumbnails.twig'
                    ]);
                }
                $collection[] = $set;
            }
            
            $renderArray['collection'] = $collection;
            
            $renderArray['pagination'] = PaginationWidget::widget([
                'pagination'=>$productsCollection->pagination,
                'view'=>'pagination.twig'
            ]);
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model свойству ProductsListIndexService::searchModel
     * @param object $model Model
     */
    public function setSearchModel(Model $model)
    {
        try {
            $this->searchModel = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
