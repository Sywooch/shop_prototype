<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

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
     * @var AbstractBaseWidget
     */
    private $categoriesForm;
    /**
     * @var AbstractBaseWidget
     */
    private $subcategoryForm;
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
            if (empty($this->categoriesForm)) {
                throw new ErrorException($this->emptyError('categoriesForm'));
            }
            if (empty($this->subcategoryForm)) {
                throw new ErrorException($this->emptyError('subcategoryForm'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            
            $categoriesArray = [];
            foreach ($this->categories as $category) {
                $setCategory = [];
                $setCategory['id'] = $category->id;
                $setCategory['name'] = $category->name;
                $setCategory['active'] = !empty($category->active) ? true : false;
                $setCategory['formIdChange'] = sprintf('admin-category-change-form-%d', $category->id);
                $setCategory['formIdDelete'] = sprintf('admin-category-delete-form-%d', $category->id);
                
                if (!empty($category->subcategory)) {
                    foreach ($category->subcategory as $subcategory) {
                        $setSubcategory = [];
                        $setSubcategory['id'] = $subcategory->id;
                        $setSubcategory['name'] = $subcategory->name;
                        $setSubcategory['active'] = !empty($subcategory->active) ? true : false;
                        $setSubcategory['formIdChange'] = sprintf('admin-subcategory-change-form-%d', $subcategory->id);
                        $setSubcategory['formIdDelete'] = sprintf('admin-subcategory-delete-form-%d', $subcategory->id);
                        $setCategory['subcategory'][] = $setSubcategory;
                    }
                    ArrayHelper::multisort($setCategory['subcategory'], 'name', SORT_ASC);
                }
                $categoriesArray[] = $setCategory;
            }
            
            ArrayHelper::multisort($categoriesArray, 'name', SORT_ASC);
            $renderArray['categories'] = $categoriesArray;
            
            $renderArray['categoryForm'] = $this->categoriesForm;
            $renderArray['categoryFormActionChange'] = Url::to(['/admin/categories-category-change']);
            $renderArray['categoryFormActionDelete'] = Url::to(['/admin/categories-category-delete']);
            
            $renderArray['subcategoryForm'] = $this->subcategoryForm;
            $renderArray['subcategoryFormActionChange'] = Url::to(['/admin/categories-subcategory-change']);
            $renderArray['subcategoryFormActionDelete'] = Url::to(['/admin/categories-subcategory-delete']);
            
            $renderArray['buttonDelete'] = \Yii::t('base', 'Delete');
            
            $renderArray['formSettings']['ajaxValidation'] = false;
            $renderArray['formSettings']['validateOnSubmit'] = false;
            $renderArray['formSettings']['validateOnChange'] = false;
            $renderArray['formSettings']['validateOnBlur'] = false;
            $renderArray['formSettings']['validateOnType'] = false;
            
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
     * Присваивает имя шаблона свойству AdminCategoriesWidget::categoriesForm
     * @param AbstractBaseForm $categoriesForm
     */
    public function setCategoriesForm(AbstractBaseForm $categoriesForm)
    {
        try {
            $this->categoriesForm = $categoriesForm;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству AdminCategoriesWidget::subcategoryForm
     * @param AbstractBaseForm $subcategoryForm
     */
    public function setSubcategoryForm(AbstractBaseForm $subcategoryForm)
    {
        try {
            $this->subcategoryForm = $subcategoryForm;
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
