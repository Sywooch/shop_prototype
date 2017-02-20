<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AdminCategoriesWidget extends AbstractBaseWidget
{
    /**
     * @var array CategoriesModel
     */
    private $categories;
    /**
     * @var string заголовок
     */
    private $header;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Конструирует HTML строку с данными
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->categories)) {
                throw new ErrorException($this->emptyError('categories'));
            }
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            
            $categoriesArray = [];
            foreach ($this->categories as $category) {
                $set = [];
                $set['name'] = $category->name;
                if (!empty($category->subcategory)) {
                    foreach ($category->subcategory as $subcategory) {
                        $set['subcategory'][] = $subcategory->name;
                    }
                }
                $categoriesArray[] = $set;
            }
            
            $renderArray['categories'] = $categoriesArray;
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminCategoriesWidget::categories
     * @param array $categories
     */
    public function setCategories(array $categories)
    {
        try {
            $this->categories = $categories;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству AdminCategoriesWidget::header
     * @param string $header
     */
    public function setHeader(string $header)
    {
        try {
            $this->header = $header;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству AdminCategoriesWidget::template
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        try {
            $this->template = $template;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
