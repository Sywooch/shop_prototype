<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\AdminProductsFiltersForm;
use app\helpers\DateHelper;

/**
 * Формирует HTML строку с формой, представляющей фильтры товаров
 */
class AdminProductsFiltersWidget extends AbstractBaseWidget
{
    /**
     * @var array
     */
    private $sortingFields;
    /**
     * @var array
     */
    private $sortingTypes;
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
     * @var array CategoriesModel
     */
    private $categories;
    /**
     * @var array статусы доступности
     */
    private $activeStatuses;
    /**
     * @var AdminProductsFiltersForm
     */
    private $form;
    /**
     * @var string заголовок
     */
    private $header;
    /**
     * @var string имя HTML шаблона
     */
    private $template;
    
    public function run()
    {
        try {
            if (empty($this->sortingFields)) {
                throw new ErrorException($this->emptyError('sortingFields'));
            }
            if (empty($this->sortingTypes)) {
                throw new ErrorException($this->emptyError('sortingTypes'));
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
            if (empty($this->categories)) {
                throw new ErrorException($this->emptyError('categories'));
            }
            if (empty($this->activeStatuses)) {
                throw new ErrorException($this->emptyError('activeStatuses'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            
            $renderArray['formModel'] = $this->form;
            
            $renderArray['sortingFields'] = $this->sortingFields;
            $renderArray['sortingTypes'] = $this->sortingTypes;
            $renderArray['colors'] = $this->colors;
            $renderArray['sizes'] = $this->sizes;
            $renderArray['brands'] = $this->brands;
            $renderArray['categories'] = $this->categories;
            $renderArray['subcategory'] = [\Yii::$app->params['formFiller']];
            $renderArray['activeStatuses'] = $this->activeStatuses;
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['categoriesHref'] = Url::to(['/categories/get-subcategory']);
            
            $renderArray['formIdApply'] = 'admin-products-filters-form';
            $renderArray['formActionApply'] = Url::to(['/filters/products-set']);
            $renderArray['buttonApply'] = \Yii::t('base', 'Apply');
            
            $renderArray['formIdClean'] = 'admin-products-filters-clean';
            $renderArray['formActionClean'] = Url::to(['/filters/products-unset']);
            $renderArray['buttonClean'] = \Yii::t('base', 'Clean');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminProductsFiltersWidget::sortingFields
     * @param array $sortingFields
     */
    public function setSortingFields(array $sortingFields)
    {
        try {
            $this->sortingFields = $sortingFields;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminProductsFiltersWidget::sortingTypes
     * @param array $sortingTypes
     */
    public function setSortingTypes(array $sortingTypes)
    {
        try {
            $this->sortingTypes = $sortingTypes;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminProductsFiltersWidget::colors
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
     * Присваивает array свойству AdminProductsFiltersWidget::sizes
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
     * Присваивает array свойству AdminProductsFiltersWidget::brands
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
    
    /**
     * Присваивает array свойству AdminProductsFiltersWidget::categories
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
     * Присваивает array свойству AdminProductsFiltersWidget::activeStatuses
     * @param array $activeStatuses
     */
    public function setActiveStatuses(array $activeStatuses)
    {
        try {
            $this->activeStatuses = $activeStatuses;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает AdminProductsFiltersForm свойству AdminProductsFiltersWidget::form
     * @param AdminProductsFiltersForm $form
     */
    public function setForm(AdminProductsFiltersForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает заголовок свойству AdminProductsFiltersWidget::header
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
     * Присваивает имя шаблона свойству AdminProductsFiltersWidget::template
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
