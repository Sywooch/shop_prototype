<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\widgets\Breadcrumbs;
use app\traits\ExceptionsTrait;
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
    public $homeLabel = 'Главная';
    /**
     * var string seocode категории для подстановки в breadcrumbs
     */
    private $categoriesSeocode;
    /**
     * var string seocode подкатегории для подстановки в breadcrumbs
     */
    private $subcategorySeocode;
    /**
     * var int ID продукта для подстановки в breadcrumbs
     */
    private $id;
    /**
     * @var string название товара для подстановки в breadcrumbs
     */
    private $productsName;
    /**
     * @var string имя категории для подстановки в breadcrumbs
     */
    private $categoriesName;
    /**
     * @var string имя подкатегории для подстановки в breadcrumbs
     */
    private $subcategoryName;
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
            
            if (empty(\Yii::$app->params['categoriesKey'])) {
                throw new ErrorException('Не определен categoriesKey!');
            }
            if (empty(\Yii::$app->params['subcategoryKey'])) {
                throw new ErrorException('Не определен subcategoryKey!');
            }
            if (empty(\Yii::$app->params['idKey'])) {
                throw new ErrorException('Не определен idKey!');
            }
            
            $this->homeLink = [
                'label'=>$this->homeLabel,
                'url'=>['/'],
            ];
            
            $this->itemTemplate = sprintf($this->itemTemplate, $this->separator);
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['categoriesKey']))) {
                $categoriesModel = CategoriesModel::find()->where(['categories.seocode'=>\Yii::$app->request->get(\Yii::$app->params['categoriesKey'])])->one();
                if ($categoriesModel instanceof CategoriesModel) {
                    $this->categoriesSeocode = $categoriesModel->seocode;
                    $this->categoriesName = $categoriesModel->name;
                }
            }
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                $subcategoryModel = SubcategoryModel::find()->where(['subcategory.seocode'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])])->one();
                if ($subcategoryModel instanceof SubcategoryModel) {
                    $this->subcategorySeocode = $subcategoryModel->seocode;
                    $this->subcategoryName = $subcategoryModel->name;
                }
            }
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                $productsModel = ProductsModel::find()->where(['products.id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])->one();
                if ($productsModel instanceof ProductsModel) {
                    $this->id = $productsModel->id;
                    $this->productsName = $productsModel->name;
                }
            }
            
            if (!$this->setLinks()) {
                throw new ErrorException('Ошибка при конструировании массива ссылок!');
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
            if (!empty($this->categoriesSeocode)) {
                $this->_breadcrumbs[] = ['url'=>['/products-list/index', 'categories'=>$this->categoriesSeocode], 'label'=>$this->categoriesName];
            }
            if (!empty($this->subcategorySeocode)) {
                $this->_breadcrumbs[] = ['url'=>['/products-list/index', 'categories'=>$this->categoriesSeocode, 'subcategory'=>$this->subcategorySeocode], 'label'=>$this->subcategoryName];
            }
            if (!empty($this->id)) {
                $this->_breadcrumbs[] = ['url'=>['/products-detail/index', 'categories'=>$this->categoriesSeocode, 'subcategory'=>$this->subcategorySeocode, 'id'=>$this->id], 'label'=>$this->productsName];
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
