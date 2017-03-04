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
                $setCategory['name'] = $category->name;
                
                $categoriesForm = clone $this->categoriesForm;
                $setCategory['modelForm'] = \Yii::configure($categoriesForm, [
                    'id'=>$category->id,
                    'active'=>$category->active,
                ]);
                
                $setCategory['formId'] = sprintf('admin-category-delete-form-%d', $category->id);
                $setCategory['formAction'] = Url::to(['/admin/categories-category-delete']);
                $setCategory['button'] = \Yii::t('base', 'Delete');
                
                $setCategory['formIdChange'] = sprintf('admin-category-change-form-%d', $category->id);
                $setCategory['formActionChange'] = Url::to(['/admin/categories-category-change']);
                
                $setCategory['ajaxValidation'] = false;
                $setCategory['validateOnSubmit'] = false;
                $setCategory['validateOnChange'] = false;
                $setCategory['validateOnBlur'] = false;
                $setCategory['validateOnType'] = false;
                
                if (!empty($category->subcategory)) {
                    foreach ($category->subcategory as $subcategory) {
                        $setSubcategory = [];
                        $setSubcategory['name'] = $subcategory->name;
                        
                        $subcategoryForm = clone $this->subcategoryForm;
                        $setSubcategory['modelForm'] = \Yii::configure($subcategoryForm, ['id'=>$subcategory->id]);
                        $setSubcategory['formId'] = sprintf('admin-subcategory-delete-form-%d', $subcategory->id);
                        
                        $setSubcategory['ajaxValidation'] = false;
                        $setSubcategory['validateOnSubmit'] = false;
                        $setSubcategory['validateOnChange'] = false;
                        $setSubcategory['validateOnBlur'] = false;
                        $setSubcategory['validateOnType'] = false;
                        
                        $setSubcategory['formAction'] = Url::to(['/admin/categories-subcategory-delete']);
                        $setSubcategory['button'] = \Yii::t('base', 'Delete');
                        
                        $setCategory['subcategory'][] = $setSubcategory;
                    }
                    ArrayHelper::multisort($setCategory['subcategory'], 'name', SORT_ASC);
                }
                $categoriesArray[] = $setCategory;
            }
            
            ArrayHelper::multisort($categoriesArray, 'name', SORT_ASC);
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
