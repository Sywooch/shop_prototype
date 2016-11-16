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
    SeeAlsoWidget,
    ToCartWidget,
    UserInfoWidget};
use app\models\{CategoriesFilter,
    CurrencyFilter,
    ProductsModel};
use app\repository\{PurchasesSessionRepository,
    RelatedProductsRepository,
    SimilarProductsRepository,
    UsersSessionRepository};
use app\services\PurchasesSessionSearchService;

class ProductDetailWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var string имя шаблона
     */
    public $view = 'product-detail.twig';
    /**
     * @var object ProductsModel
     */
    private $model;
    
    public function run()
    {
        try {
            $renderArray = [];
            $renderArray['user'] = UserInfoWidget::widget(['repository'=>new UsersSessionRepository(), 'view'=>'user-info.twig']);
            $renderArray['cart'] = CartWidget::widget(['service'=>new PurchasesSessionSearchService(new PurchasesSessionRepository()), 'view'=>'short-cart.twig']);
            $renderArray['search'] = SearchWidget::widget(['view'=>'search.twig']);
            $renderArray['menu'] = CategoriesMenuWidget::widget(['filterClass'=>new CategoriesFilter(), 'filterScenario'=>'menuSearch']);
            $renderArray['breadcrumbs'] = ProductBreadcrumbsWidget::widget(['model'=>$this->model]);
            $renderArray['toCart'] = ToCartWidget::widget(['view'=>'add-to-cart-form.twig', 'product'=>$this->model]);
            $renderArray['currency'] = CurrencyWidget::widget(['filterClass'=>new CurrencyFilter(), 'filterScenario'=>'widgetSearch', 'view'=>'currency-form.twig']);
            $renderArray['similar'] = SeeAlsoWidget::widget(['data'=>(new SimilarProductsRepository())->getGroup($this->model), 'text'=>\Yii::t('base', 'Similar products:')]);
            $renderArray['related'] = SeeAlsoWidget::widget(['data'=>(new RelatedProductsRepository())->getGroup($this->model), 'text'=>\Yii::t('base', 'Related products:')]);
            
            $renderArray['name'] = $this->model->name;
            $renderArray['description'] = $this->model->description;
            if ($this->model->images) {
                $renderArray['images'] = ImagesWidget::widget(['path'=>$this->model->images, 'view'=>'images.twig']);
            }
            $renderArray['colors'] = ArrayHelper::getColumn($this->model->colors, 'color');
            $renderArray['sizes'] = ArrayHelper::getColumn($this->model->sizes, 'size');
            $renderArray['price'] = PriceWidget::widget(['price'=>$this->model->price]);
            $renderArray['code'] = $this->model->code;
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function setModel(ProductsModel $model)
    {
        try {
            $this->model = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
