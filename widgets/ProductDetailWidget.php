<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model,
    Widget};
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\widgets\{CartWidget,
    CategoriesMenuWidget,
    CurrencyWidget,
    ImagesWidget,
    PriceWidget,
    ProductBreadcrumbsWidget,
    SearchWidget,
    SeeAlsoRelatedWidget,
    SeeAlsoSimilarWidget,
    ToCartWidget,
    UserInfoWidget};
use app\models\{CategoriesModel,
    Collection,
    CurrencyModel,
    ProductsModel,
    PurchasesModel,
    User};
use app\repositories\{DbRepository,
    SessionRepository};

class ProductDetailWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object ActiveRecord/Model
     */
    private $model;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model)) {
                throw new ErrorException(ExceptionsTrait::emptyError('model'));
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
                'user'=>new User()
            ]);
            $renderArray['cart'] = CartWidget::widget([
                'repository'=>new SessionRepository([
                    'items'=>new Collection(),
                    'class'=>PurchasesModel::class
                ]), 
                'currency'=>new SessionRepository([
                    'class'=>CurrencyModel::class
                ]), 
                'view'=>'short-cart.twig'
            ]);
            $renderArray['search'] = SearchWidget::widget([
                'view'=>'search.twig'
            ]);
            $renderArray['menu'] = CategoriesMenuWidget::widget([
                'repository'=>new DbRepository([
                    'items'=>new Collection(),
                    'class'=>CategoriesModel::class
                ])
            ]);
            $renderArray['breadcrumbs'] = ProductBreadcrumbsWidget::widget([
                'model'=>$this->model
            ]);
            $renderArray['toCart'] = ToCartWidget::widget([
                'model'=>$this->model,
                'purchase'=>new PurchasesModel(['quantity'=>1]),
                'view'=>'add-to-cart-form.twig',
            ]);
            $renderArray['currency'] = CurrencyWidget::widget([
                'repository'=>new DbRepository([
                    'items'=>new Collection(),
                    'class'=>CurrencyModel::class
                ]),
                'currency'=>new CurrencyModel(),
                'view'=>'currency-form.twig'
            ]);
            $renderArray['similar'] = SeeAlsoSimilarWidget::widget([
                'repository'=>new DbRepository([
                    'items'=>new Collection(),
                    'class'=>ProductsModel::class
                ]), 
                'model'=>$this->model, 
                'text'=>\Yii::t('base', 'Similar products:'), 
                'view'=>'see-also.twig'
            ]);
            $renderArray['related'] = SeeAlsoRelatedWidget::widget([
                'repository'=>new DbRepository([
                    'items'=>new Collection(),
                    'class'=>ProductsModel::class
                ]),
                'model'=>$this->model, 
                'text'=>\Yii::t('base', 'Related products:'), 
                'view'=>'see-also.twig'
            ]);
            
            $renderArray['name'] = $this->model->name;
            $renderArray['description'] = $this->model->description;
            if ($this->model->images) {
                $renderArray['images'] = ImagesWidget::widget([
                    'path'=>$this->model->images, 
                    'view'=>'images.twig'
                ]);
            }
            $renderArray['colors'] = ArrayHelper::getColumn($this->model->colors, 'color');
            $renderArray['sizes'] = ArrayHelper::getColumn($this->model->sizes, 'size');
            $renderArray['price'] = PriceWidget::widget([
                'repository'=>new SessionRepository([
                    'class'=>CurrencyModel::class
                ]), 
                'price'=>$this->model->price
            ]);
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
}
