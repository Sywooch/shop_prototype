<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\CurrencyInterface;
use app\forms\AbstractBaseForm;
use app\helpers\ImgHelper;

/**
 * Формирует HTML строку с данными товара
 */
class AdminAddProductFormWidget extends AbstractBaseWidget
{
    /**
     * @var array CategoriesModel
     */
    private $categories;
    /**
     * @var array SubcategoryModel
     */
    private $subcategory;
    /**
     * @var array ColorsModel
     */
    private $colors;
    /**
     * @var array SizesModel
     */
    private $sizes;
    /**
     * @var array BrandsModel
     */
    private $brands;
    /**
     * @var AbstractBaseForm
     */
    private $form;
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
            if (empty($this->subcategory)) {
                throw new ErrorException($this->emptyError('subcategory'));
            }
            if (empty($this->colors)) {
                throw new ErrorException($this->emptyError('colors'));
            }
            if (empty($this->sizes)) {
                throw new ErrorException($this->emptyError('sizes'));
            }
            if (empty($this->brands)) {
                throw new ErrorException($this->emptyError('brands'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['modelForm'] = $this->form;
            $renderArray['formId'] = 'admin-add-product-form';
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formAction'] = Url::to(['/admin/add-product-post']);
            $renderArray['button'] = \Yii::t('base', 'Save');
            
            $renderArray['categoriesHref'] = Url::to(['/categories/get-subcategory']);
            $renderArray['cols'] = 30;
            $renderArray['rows'] = 10;
            
            $renderArray['categories'] = $this->categories;
            $renderArray['subcategory'] = $this->subcategory;
            $renderArray['colors'] = $this->colors;
            $renderArray['sizes'] = $this->sizes;
            $renderArray['brands'] = $this->brands;
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству AdminAddProductFormWidget::form
     * @param AbstractBaseForm $form
     */
    public function setForm(AbstractBaseForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству AdminAddProductFormWidget::template
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
    
    /**
     * Присваивает array CategoriesModel свойству AdminAddProductFormWidget::categories
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
     * Присваивает array свойству AdminAddProductFormWidget::subcategory
     * @param array $subcategory
     */
    public function setSubcategory(array $subcategory)
    {
        try {
            $this->subcategory = $subcategory;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminAddProductFormWidget::colors
     * @param array $colors
     */
    public function setColors(array $colors)
    {
        try {
            $this->colors = $colors;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminAddProductFormWidget::sizes
     * @param array $sizes
     */
    public function setSizes(array $sizes)
    {
        try {
            $this->sizes = $sizes;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminAddProductFormWidget::brands
     * @param array $brands
     */
    public function setBrands(array $brands)
    {
        try {
            $this->brands = $brands;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
