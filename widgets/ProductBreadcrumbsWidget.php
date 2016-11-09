<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\BreadcrumbsWidget;

/**
 * Формирует breadcrumbs для страницы товара
 */
class ProductBreadcrumbsWidget extends BreadcrumbsWidget
{
    /**
     * @var object ProductsModel
     */
    public $model;
    
    public function init()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>$this->model->category->seocode], 'label'=>$this->model->category->name];
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>$this->model->category->seocode, \Yii::$app->params['subcategoryKey']=>$this->model->subcategory->seocode], 'label'=>$this->model->subcategory->name];
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/product-detail/index', \Yii::$app->params['productKey']=>$this->model->seocode], 'label'=>$this->model->name];
            
            parent::init();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
