<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model,
    Widget};
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use app\exceptions\ExceptionsTrait;
use app\models\{CollectionInterface,
    CurrencyModel};
use app\widgets\{PaginationWidget,
    PriceWidget,
    ThumbnailsWidget};
use app\repositories\SessionRepository;
use app\finders\FinderInterface;

/**
 * Формирует HTML строку, представляющую каталог товаров
 */
class ProductsListWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object FinderInterface
     */
    private $finder;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->finder)) {
                throw new ErrorException(ExceptionsTrait::emptyError('finder'));
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
            $productsCollection = $this->finder->find();
            
            if ($productsCollection->isEmpty()) {
                throw new NotFoundHttpException(ExceptionsTrait::Error404());
            }
            
            $renderArray = [];
            
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
                $renderArray['collection'][] = $set;
            }
            
            $renderArray['pagination'] = PaginationWidget::widget([
                'pagination'=>$productsCollection->pagination,
                'view'=>'pagination.twig'
            ]);
            
            return $this->render($this->view, $renderArray);
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает FinderInterface свойству ProductsListIndexService::searchModel
     * @param object $model FinderInterface
     */
    public function setFinder(FinderInterface $finder)
    {
        try {
            $this->finder = $finder;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
