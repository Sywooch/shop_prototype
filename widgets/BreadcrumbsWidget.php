<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\widgets\Breadcrumbs;
use app\exceptions\ExceptionsTrait;
use app\models\{CategoriesModel,
    ProductsModel,
    SubcategoryModel};

/**
 * Формирует breadcrumbs
 */
class BreadcrumbsWidget extends Breadcrumbs
{
    use ExceptionsTrait;
    
     /**
     * @var string имя ссылки на весь каталог
     */
    public $homeLabel;
    /**
     * var string seocode категории для подстановки в breadcrumbs
     */
    private $_categorySeocode;
    /**
     * var string seocode подкатегории для подстановки в breadcrumbs
     */
    private $_subcategorySeocode;
    /**
     * var int ID продукта для подстановки в breadcrumbs
     */
    private $_id;
    /**
     * @var string название товара для подстановки в breadcrumbs
     */
    private $_productsName;
    /**
     * @var string имя категории для подстановки в breadcrumbs
     */
    private $_categoryName;
    /**
     * @var string имя подкатегории для подстановки в breadcrumbs
     */
    private $_subcategoryName;
    /**
     * @var array массив ссылок для конструирования breadcrumbs
     */
    private $_breadcrumbs = [];
    /*
     * @var string шаблон для неактивного пункта
     */
    public $itemTemplate = "<li>{link}</li><li class=\"separator\">%s</li>";
    /*
     * @var string шаблон для активного пункта
     */
    public $activeItemTemplate = "<li class=\"active\">{link}</li>";
    /**
     * @var string разделитель ссылок
     */
    public $separator = ' -> ';
    
    public function init()
    {
        try {
            parent::init();
            
            $this->homeLabel = \Yii::t('base', 'Home');
            
            $this->homeLink = [
                'label'=>$this->homeLabel,
                'url'=>['/'],
            ];
            
            $this->itemTemplate = sprintf($this->itemTemplate, $this->separator);
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
                $categoriesModel = CategoriesModel::find()->where(['categories.seocode'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])])->one();
                if ($categoriesModel instanceof CategoriesModel) {
                    $this->_categorySeocode = $categoriesModel->seocode;
                    $this->_categoryName = $categoriesModel->name;
                }
            }
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                $subcategoryModel = SubcategoryModel::find()->where(['subcategory.seocode'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])])->one();
                if ($subcategoryModel instanceof SubcategoryModel) {
                    $this->_subcategorySeocode = $subcategoryModel->seocode;
                    $this->_subcategoryName = $subcategoryModel->name;
                }
            }
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                $productsModel = ProductsModel::find()->where(['products.id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])->one();
                if ($productsModel instanceof ProductsModel) {
                    $this->_id = $productsModel->id;
                    $this->_productsName = $productsModel->name;
                }
            }
            
            if (!$this->setLinks()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error!'));
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Конструирует массив ссылок breadcrumbs на основе данных $_GET
     * @return bool
     */
    private function setLinks()
    {
        try {
            if (!empty($this->_categorySeocode)) {
                $this->_breadcrumbs[] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>$this->_categorySeocode], 'label'=>$this->_categoryName];
            }
            if (!empty($this->_subcategorySeocode)) {
                $this->_breadcrumbs[] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>$this->_categorySeocode, \Yii::$app->params['subcategoryKey']=>$this->_subcategorySeocode], 'label'=>$this->_subcategoryName];
            }
            if (!empty($this->_id)) {
                $this->_breadcrumbs[] = ['url'=>['/product-detail/index', \Yii::$app->params['categoryKey']=>$this->_categorySeocode, \Yii::$app->params['subcategoryKey']=>$this->_subcategorySeocode, \Yii::$app->params['idKey']=>$this->_id], 'label'=>$this->_productsName];
            }
            
            if (!empty($this->_breadcrumbs)) {
                $tail = array_pop($this->_breadcrumbs);
                
                $this->links = $this->_breadcrumbs;
                
                unset($tail['url']);
                
                $this->links[] = $tail;
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
