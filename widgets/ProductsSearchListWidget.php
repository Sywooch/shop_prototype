<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\Html;
use app\exceptions\ExceptionsTrait;
use app\models\{BrandsModel,
    CategoriesModel,
    ChangeCurrencyFormModel,
    CollectionInterface,
    CurrencyModel,
    BaseCollection,
    ColorsModel,
    ProductsFiltersFormModel,
    PurchasesCollection,
    PurchasesModel,
    SizesModel,
    SphinxModel};
use app\widgets\{CategoriesMenuWidget,
    CartWidget,
    CurrencyWidget,
    FiltersWidget,
    PaginationWidget,
    SearchBreadcrumbsWidget,
    ThumbnailsWidget,
    SearchWidget,
    PriceWidget,
    UserInfoWidget};
use app\repositories\{DbRepository,
    SessionRepository};
use app\queries\QueryCriteria;

class ProductsSearchListWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object CollectionInterface
     */
    private $collection;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->collection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('collection'));
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
            $renderArray = [];
            
            $renderArray['user'] = UserInfoWidget::widget([
                'view'=>'user-info.twig',
                'user'=>\Yii::$app->user
            ]);
            
            $renderArray['cart'] = CartWidget::widget([
                'repository'=>new SessionRepository([
                    'collection'=>new PurchasesCollection(),
                    'class'=>PurchasesModel::class
                ]), 
                'repositoryCurrency'=>new SessionRepository([
                    'class'=>CurrencyModel::class
                ]), 
                'view'=>'short-cart.twig'
            ]);
            
            $renderArray['currency'] = CurrencyWidget::widget([
                'repository'=>new DbRepository([
                    'collection'=>new BaseCollection(),
                    'query'=>CurrencyModel::find(),
                    'criteria'=>new QueryCriteria()
                ]),
                'form'=>new ChangeCurrencyFormModel(),
                'view'=>'currency-form.twig'
            ]);
            
            $renderArray['search'] = SearchWidget::widget([
                'view'=>'search.twig'
            ]);
            
            $renderArray['menu'] = CategoriesMenuWidget::widget([
                'repository'=>new DbRepository([
                    'collection'=>new BaseCollection(),
                    'query'=>CategoriesModel::find(),
                    'criteria'=>new QueryCriteria()
                ])
            ]);
            
            $renderArray['breadcrumbs'] = SearchBreadcrumbsWidget::widget();
            
            $renderArray['pagination'] = PaginationWidget::widget([
                'pagination'=>$this->collection->pagination,
                'view'=>'pagination.twig'
            ]);
            
            $renderArray['filters'] = FiltersWidget::widget([
                'colorsRepository'=>new DbRepository([
                    'query'=>ColorsModel::find(),
                    'collection'=>new BaseCollection(),
                    'criteria'=>new QueryCriteria()
                ]),
                'sizesRepository'=>new DbRepository([
                    'query'=>SizesModel::find(),
                    'collection'=>new BaseCollection(),
                    'criteria'=>new QueryCriteria()
                ]),
                'brandsRepository'=>new DbRepository([
                    'query'=>BrandsModel::find(),
                    'collection'=>new BaseCollection(),
                    'criteria'=>new QueryCriteria()
                ]),
                'sphinxRepository'=>new DbRepository([
                    'query'=>SphinxModel::find(),
                    'collection'=>new BaseCollection(),
                    'criteria'=>new QueryCriteria()
                ]),
                'form'=>new ProductsFiltersFormModel(),
                'view'=>'products-filters.twig'
            ]);
            
            $collection = [];
            foreach ($this->collection as $good) {
                $set = [];
                $set['link'] = Html::a($good->name, ['product-detail/index', 'seocode'=>$good->seocode]);
                $set['short_description'] = $good->short_description;
                $set['price'] = PriceWidget::widget([
                    'repository'=>new SessionRepository([
                        'class'=>CurrencyModel::class
                    ]), 
                    'price'=>$good->price
                ]);
                if (!empty($good->images)) {
                    $set['images'] = ThumbnailsWidget::widget([
                        'path'=>$good->images, 
                        'view'=>'thumbnails.twig'
                    ]);
                }
                $collection[] = $set;
            }
            $renderArray['collection'] = $collection;
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству GoodsListWidget::collection
     * @param object $collection CollectionInterface
     */
    public function setCollection(CollectionInterface $collection)
    {
        try {
            $this->collection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
