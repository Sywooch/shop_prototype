<?php

namespace app\widgets;

use yii\base\{ErrorException,
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
    SeeAlsoWidget,
    ToCartWidget,
    UserInfoWidget};
use app\models\{CategoriesComposit,
    CurrencyComposit,
    ProductsComposit,
    ProductsModel,
    PurchasesComposit};
use app\repository\{CategoriesRepository,
    CurrencySessionRepository,
    CurrencyRepository,
    ProductsRepository,
    PurchasesSessionRepository,
    RelatedProductsRepository,
    SimilarProductsRepository};

class ProductDetailWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object ProductsModel
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
                'view'=>'user-info.twig'
            ]);
            $renderArray['cart'] = CartWidget::widget([
                'repository'=>new PurchasesSessionRepository(['items'=>new PurchasesComposit()]), 
                'currency'=>new CurrencySessionRepository(), 
                'view'=>'short-cart.twig'
            ]);
            $renderArray['search'] = SearchWidget::widget([
                'view'=>'search.twig'
            ]);
            $renderArray['menu'] = CategoriesMenuWidget::widget([
                'repository'=>new CategoriesRepository(['items'=>new CategoriesComposit()])
            ]);
            $renderArray['breadcrumbs'] = ProductBreadcrumbsWidget::widget([
                'model'=>$this->model
            ]);
            $renderArray['toCart'] = ToCartWidget::widget([
                'view'=>'add-to-cart-form.twig', 
                'model'=>$this->model
            ]);
            $renderArray['currency'] = CurrencyWidget::widget([
                'repository'=>new CurrencyRepository(['items'=>new CurrencyComposit()]), 
                'view'=>'currency-form.twig'
            ]);
            $renderArray['similar'] = SeeAlsoSimilarWidget::widget([
                'repository'=>new ProductsRepository(['items'=>new ProductsComposit()]), 
                'model'=>$this->model, 
                'text'=>\Yii::t('base', 'Similar products:'), 
                'view'=>'see-also.twig'
            ]);
            $renderArray['related'] = SeeAlsoRelatedWidget::widget([
                'repository'=>new ProductsRepository(['items'=>new ProductsComposit()]), 
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
                'repository'=>new CurrencySessionRepository(), 
                'price'=>$this->model->price
            ]);
            $renderArray['code'] = $this->model->code;
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsModel свойству ProductDetailWidget::model
     * @param object $model ProductsModel
     */
    public function setModel(ProductsModel $model)
    {
        try {
            $this->model = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
