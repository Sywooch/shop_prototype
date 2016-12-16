<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\BreadcrumbsWidget;
use app\exceptions\ExceptionsTrait;
use app\models\{CategoriesModel,
    SubcategoryModel};

/**
 * Формирует breadcrumbs для страницы товара
 */
class CategoriesBreadcrumbsWidget extends BreadcrumbsWidget
{
    use ExceptionsTrait;
    
    /**
     * @var CategoriesModel
     */
    private $category;
    /**
     * @var SubcategoryModel
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
     * Присваивает CategoriesModel свойству CategoriesBreadcrumbsWidget::category
     * @param CategoriesModel $category
     */
    public function setCategory(CategoriesModel $category)
    {
        try {
            $this->category = $category;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает SubcategoryModel свойству CategoriesBreadcrumbsWidget::subcategory
     * @param SubcategoryModel $subcategory
     */
    public function setSubcategory(SubcategoryModel $subcategory)
    {
        try {
            $this->subcategory = $subcategory;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
