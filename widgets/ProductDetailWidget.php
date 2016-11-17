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
use app\models\ProductsModel;
use app\repository\{CategoriesRepository,
    CurrencySessionRepository,
    CurrencyRepository,
    PurchasesSessionRepository,
    RelatedProductsRepository,
    SimilarProductsRepository};

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
            $renderArray['user'] = UserInfoWidget::widget(['view'=>'user-info.twig']);
            $renderArray['cart'] = CartWidget::widget(['repository'=>new PurchasesSessionRepository(), 'currency'=>new CurrencySessionRepository(), 'view'=>'short-cart.twig']);
            $renderArray['search'] = SearchWidget::widget(['view'=>'search.twig']);
            $renderArray['menu'] = CategoriesMenuWidget::widget(['repository'=>new CategoriesRepository()]);
            $renderArray['breadcrumbs'] = ProductBreadcrumbsWidget::widget(['model'=>$this->model]);
            $renderArray['toCart'] = ToCartWidget::widget(['view'=>'add-to-cart-form.twig', 'model'=>$this->model]);
            $renderArray['currency'] = CurrencyWidget::widget(['repository'=>new CurrencyRepository(), 'view'=>'currency-form.twig']);
            $renderArray['similar'] = SeeAlsoWidget::widget(['repository'=>new SimilarProductsRepository(), 'model'=>$this->model, 'text'=>\Yii::t('base', 'Similar products:'), 'view'=>'see-also.twig']);
            $renderArray['related'] = SeeAlsoWidget::widget(['repository'=>new RelatedProductsRepository(), 'model'=>$this->model, 'text'=>\Yii::t('base', 'Related products:'), 'view'=>'see-also.twig']);
            
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
