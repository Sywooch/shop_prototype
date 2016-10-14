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
     * var string seocode категории для подстановки в breadcrumbs
     */
    private $_categorySeocode;
    /**
     * var string seocode подкатегории для подстановки в breadcrumbs
     */
    private $_subcategorySeocode;
    /**
     * var string seocode продукта для подстановки в breadcrumbs
     */
    private $_productSeocode;
    /**
     * @var string название товара для подстановки в breadcrumbs
     */
    private $_productName;
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
    /**
     * @var object ProductsModel для которого строится цепь ссылок
     */
    public $productsModel;
    
    public function init()
    {
        try {
            parent::init();
            
            $this->homeLink = [
                'label'=>\Yii::t('base', 'Home'),
                'url'=>['/'],
            ];
            
            $this->itemTemplate = sprintf($this->itemTemplate, $this->separator);
            
            if (!empty($this->productsModel)) {
                if (!empty(\Yii::$app->request->get(\Yii::$app->params['productKey']))) {
                    $this->_productName = $this->productsModel->name;
                    $this->_productSeocode = $this->productsModel->seocode;
                    $this->_categoryName = $this->productsModel->categoryName;
                    $this->_categorySeocode = $this->productsModel->categorySeocode;
                    $this->_subcategoryName = $this->productsModel->subcategoryName;
                    $this->_subcategorySeocode = $this->productsModel->subcategorySeocode;
                } else {
                    if (!empty(\Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
                        $this->_categoryName = $this->productsModel->categoryName;
                        $this->_categorySeocode = $this->productsModel->categorySeocode;
                    }
                    if (!empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                        $this->_subcategoryName = $this->productsModel->subcategoryName;
                        $this->_subcategorySeocode = $this->productsModel->subcategorySeocode;
                    }
                }
            }
            
            if (!empty(\Yii::$app->params['breadcrumbs'])) {
                $this->_breadcrumbs[] = \Yii::$app->params['breadcrumbs'];
            }
            
            if (!$this->setLinks()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'setLinks()']));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
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
            if (!empty($this->_productSeocode)) {
                $this->_breadcrumbs[] = ['url'=>['/product-detail/index', \Yii::$app->params['productKey']=>$this->_productSeocode], 'label'=>$this->_productName];
            }
            
            if (!empty($this->_breadcrumbs)) {
                $tail = array_pop($this->_breadcrumbs);
                
                $this->links = $this->_breadcrumbs;
                
                unset($tail['url']);
                
                $this->links[] = $tail;
            }
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
