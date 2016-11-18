<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\BreadcrumbsWidget;
use app\models\ProductsModel;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует breadcrumbs для страницы товара
 */
class ProductBreadcrumbsWidget extends BreadcrumbsWidget
{
    use ExceptionsTrait;
    
    /**
     * @var object ProductsModel
     */
    private $model;
    
    public function init()
    {
        try {
            if (empty($this->model)) {
                throw new ErrorException(ExceptionsTrait::emptyError('model'));
            }
            
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>$this->model->category->seocode], 'label'=>$this->model->category->name];
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>$this->model->category->seocode, \Yii::$app->params['subcategoryKey']=>$this->model->subcategory->seocode], 'label'=>$this->model->subcategory->name];
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/product-detail/index', \Yii::$app->params['productKey']=>$this->model->seocode], 'label'=>$this->model->name];
            
            parent::init();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsModel свойству ProductBreadcrumbsWidget::model
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
