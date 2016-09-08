<?php

namespace app\widgets;

use yii\base\{Widget,
    ErrorException};
use yii\helpers\Url;
use app\traits\ExceptionsTrait;
use app\models\{CategoriesModel,
    ProductsModel,
    SubcategoryModel};

/**
 * Формирует breadcrumbs
 */
class BreadcrumbsWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var string символ-разделитель путей в breadcrumbs
     */
    public $glue = '->';
     /**
     * @var string имя ссылки на весь каталог
     */
    public $all = 'Весь каталог';
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
    /**
     * @var string результат в формате HTML
     */
    private $_url = '';
    
    public function init()
    {
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
    }
    
    /**
     * Конструирует HTML строку breadcrumbs на основе данных $_GET
     * @return string
     */
    public function run()
    {
        try {
            $this->_breadcrumbs[] = ['url'=>Url::to(['/products-list/index']), 'text'=>$this->all];
            if (!empty($this->categoriesSeocode)) {
                $this->_breadcrumbs[] = ['url'=>Url::to(['/products-list/index', 'categories'=>$this->categoriesSeocode]), 'text'=>$this->categoriesName];
            }
            if (!empty($this->subcategorySeocode)) {
                $this->_breadcrumbs[] = ['url'=>Url::to(['/products-list/index', 'categories'=>$this->categoriesSeocode, 'subcategory'=>$this->subcategorySeocode]), 'text'=>$this->subcategoryName];
            }
            if (!empty($this->id)) {
                $this->_breadcrumbs[] = ['url'=>Url::to(['/products-detail/index', 'categories'=>$this->categoriesSeocode, 'subcategory'=>$this->subcategorySeocode, 'id'=>$this->id]), 'text'=>$this->productsName];
            }
            
            if (count($this->_breadcrumbs) > 1) {
                list($base, $tail) = array_chunk($this->_breadcrumbs, count($this->_breadcrumbs)-1);
                
                foreach ($base as $elm) {
                    $this->_url .= $this->createUrl($elm['url'], $elm['text']) . $this->glue;
                }
                
                $this->_url .= $tail[0]['text'];
            }
            
            return $this->_url;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Конструирует HTML ссылку
     * @param string $url URL для формирования ссылки
     * @param string $text текст ссылки
     * @return string
     */
    private function createUrl($url, $text)
    {
        try {
            return '<a href="' . $url . '">' . $text . '</a>';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
