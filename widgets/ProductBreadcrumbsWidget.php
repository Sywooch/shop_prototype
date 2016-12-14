<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
use app\widgets\BreadcrumbsWidget;
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;

/**
 * Формирует breadcrumbs для страницы товара
 */
class ProductBreadcrumbsWidget extends BreadcrumbsWidget
{
    use ExceptionsTrait;
    
    /**
     * @var ProductsModel
     */
    private $product;
    
    public function init()
    {
        try {
            if (empty($this->product)) {
                throw new ErrorException($this->emptyError('product'));
            }
            
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>$this->product->category->seocode], 'label'=>$this->product->category->name];
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>$this->product->category->seocode, \Yii::$app->params['subcategoryKey']=>$this->product->subcategory->seocode], 'label'=>$this->product->subcategory->name];
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/product-detail/index', \Yii::$app->params['productKey']=>$this->product->seocode], 'label'=>$this->product->name];
            
            parent::init();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsModel свойству ProductBreadcrumbsWidget::model
     * @param ProductsModel $model
     */
    public function setProduct(ProductsModel $product)
    {
        try {
            $this->product = $product;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
