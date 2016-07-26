<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Url;
use yii\base\ErrorException;
use app\traits\ExceptionsTrait;

/**
 * Формирует breadcrumbs
 */
class BreadcrumbsWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * var string строка из URL, определяющая категорию
     */
    public $categories = '';
    /**
     * var string строка из URL, определяющая подкатегорию
     */
    public $subcategory = '';
    /**
     * var string строка из URL, определяющая ID продукта
     */
    public $id = '';
    /**
     * @var string название товара для подстановки в breadcrumbs
     */
    public $productName = '';
    /**
     * @var string имя категории для подстановки в breadcrumbs
     */
    public $categoriesName = '';
    /**
     * @var string имя подкатегории для подстановки в breadcrumbs
     */
    public $subcategoryName = '';
    /**
     * @var string символ-разделитель путей в breadcrumbs
     */
    public $glue = '->';
    /**
     * @var string имя ссылки на весь каталог
     */
    public $all = 'Весь каталог';
    /**
     * @var array of objects массив объектов CategoriesModel
     */
    public $categoriesList;
    
    /**
     * @var array массив ссылок для конструирования breadcrumbs
     */
    private $_breadcrumbs = [];
    
    public function init()
    {
        parent::init();
        
        if ($categories = \Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
            $this->categories = $categories;
        }
        if ($subcategory = \Yii::$app->request->get(\Yii::$app->params['subCategoryKey'])) {
            $this->subcategory = $subcategory;
        }
        if ($id = \Yii::$app->request->get(\Yii::$app->params['idKey'])) {
            $this->id = $id;
        }
        
        if (empty($this->categoriesList)) {
            throw new ErrorException('Не передан массив объектов категорий!');
        }
        
        if (!$this->getCategoriesSubcategoryNames()) {
            throw new ErrorException('Ошибка при получении имен категорий!');
        }
    }
    
    public function run()
    {
        try {
            $this->_breadcrumbs[] = $this->createUrl(Url::to(['/products-list/index']), $this->all);
            if (!empty($this->categories)) {
                $this->_breadcrumbs[] = $this->createUrl(Url::to(['/products-list/index', 'categories'=>$this->categories]), $this->categoriesName);
            }
            if (!empty($this->subcategory)) {
                $this->_breadcrumbs[] = $this->createUrl(Url::to(['/products-list/index', 'categories'=>$this->categories, 'subcategory'=>$this->subcategory]), $this->subcategoryName);
            }
            if (!empty($this->id)) {
                $this->_breadcrumbs[] = $this->createUrl(Url::to(['/products-detail/index', 'categories'=>$this->categories, 'subcategory'=>$this->subcategory, 'id'=>$this->id]), $this->productName);
            }
            
            $last = strip_tags(array_pop($this->_breadcrumbs));
            $resultArray = empty($this->_breadcrumbs) ? [] : array_merge($this->_breadcrumbs, [$last]);
            return implode($this->glue, $resultArray);
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
    
    /**
     * Выбирает имена категории и подкатегории из массива $this->categoriesList
     * @return boolean
     */
    private function getCategoriesSubcategoryNames()
    {
        try {
            if (!empty($this->categories)) {
                foreach ($this->categoriesList as $objectCategories) {
                    if ($objectCategories->seocode == $this->categories) {
                        $this->categoriesName = $objectCategories->name;
                        if (!empty($this->subcategory)) {
                            foreach ($objectCategories->subcategory as $objectSubcategory) {
                                if ($objectSubcategory->seocode == $this->subcategory) {
                                    $this->subcategoryName = $objectSubcategory->name;
                                }
                            }
                        }
                        break;
                    }
                }
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
