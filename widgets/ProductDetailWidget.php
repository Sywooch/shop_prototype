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
use app\services\SimilarProductsSearchService;

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
    /**
     * @var array массив данных для передачи в шаблон
     */
    private $renderArray = [];
    
    public function run()
    {
        try {
            $this->renderArray['user'] = UserInfoWidget::widget(['filterClass'=>new \app\models\UsersFilter(), 'filterScenario'=>'sessionSearch', 'view'=>'user-info.twig']);
            $this->renderArray['cart'] = CartWidget::widget(['filterClass'=>new \app\models\PurchasesFilter(), 'filterScenario'=>'sessionSearch', 'view'=>'short-cart.twig']);
            $this->renderArray['search'] = SearchWidget::widget(['view'=>'search.twig']);
            $this->renderArray['menu'] = CategoriesMenuWidget::widget(['filterClass'=>new CategoriesFilter(), 'filterScenario'=>'menuSearch']);
            $this->renderArray['breadcrumbs'] = ProductBreadcrumbsWidget::widget(['model'=>$this->model]);
            $this->renderArray['toCart'] = ToCartWidget::widget(['view'=>'add-to-cart-form.twig', 'product'=>$this->model]);
            $this->renderArray['currency'] = CurrencyWidget::widget(['filterClass'=>new CurrencyFilter(), 'filterScenario'=>'widgetSearch', 'view'=>'currency-form.twig']);
            $this->renderArray['similar'] = SeeAlsoWidget::widget(['data'=>$this->model->getSimilar(new SimilarProductsSearchService()), 'text'=>\Yii::t('base', 'Similar products:')]);
            $this->renderArray['related'] = SeeAlsoWidget::widget(['data'=>$this->model->related, 'text'=>\Yii::t('base', 'Related products:')]);
            
            $this->renderArray['name'] = $this->model->name;
            $this->renderArray['description'] = $this->model->description;
            if ($this->model->images) {
                $this->renderArray['images'] = ImagesWidget::widget(['path'=>$this->model->images, 'view'=>'images.twig']);
            }
            $this->renderArray['colors'] = ArrayHelper::getColumn($this->model->colors, 'color');
            $this->renderArray['sizes'] = ArrayHelper::getColumn($this->model->sizes, 'size');
            $this->renderArray['price'] = PriceWidget::widget(['price'=>$this->model->price]);
            $this->renderArray['code'] = $this->model->code;
            
            return $this->render($this->view, $this->renderArray);
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
