<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
use app\widgets\BreadcrumbsWidget;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует breadcrumbs для страницы аккаунта
 */
class AdminProductDetailBreadcrumbsWidget extends BreadcrumbsWidget
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
            
            \Yii::$app->params['breadcrumbs'][] = ['label'=>\Yii::t('base', 'Basic data'), 'url'=>['/admin/index']];
            \Yii::$app->params['breadcrumbs'][] = ['label'=>\Yii::t('base', 'Products'), 'url'=>['/admin/products']];
            \Yii::$app->params['breadcrumbs'][] = ['label'=>$this->product->name, 'url'=>['/admin/product-detail-form', \Yii::$app->params['productId']=>$this->product->id]];
            
            parent::init();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminProductDetailBreadcrumbsWidget::product
     * @param Model $product
     */
    public function setProduct(Model $product)
    {
        try {
            $this->product = $product;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
