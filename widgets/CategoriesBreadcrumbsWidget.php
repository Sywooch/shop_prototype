<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
use app\widgets\BreadcrumbsWidget;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует breadcrumbs для страницы товара
 */
class CategoriesBreadcrumbsWidget extends BreadcrumbsWidget
{
    use ExceptionsTrait;
    
    /**
     * @var object Model
     */
    private $category;
    /**
     * @var object Model
     */
    private $subcategory;
    
    public function init()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            
            if (!empty($this->category)) {
                \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>$this->category->seocode], 'label'=>$this->category->name];
                
                if (!empty($this->subcategory)) {
                    \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>$this->category->seocode, \Yii::$app->params['subcategoryKey']=>$this->subcategory->seocode], 'label'=>$this->subcategory->name];
                }
            }
            
            parent::init();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model свойству CategoriesBreadcrumbsWidget::category
     * @param object $category Model
     */
    public function setCategory(Model $model)
    {
        try {
            $this->category = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model свойству CategoriesBreadcrumbsWidget::subcategory
     * @param object $subcategory Model
     */
    public function setSubcategory(Model $model)
    {
        try {
            $this->subcategory = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
