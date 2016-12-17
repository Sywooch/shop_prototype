<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\FiltersForm;

/**
 * Формирует HTML строку с формой, представляющей фильтры товаров
 */
class FiltersWidget extends AbstractBaseWidget
{
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
     * @var array
     */
    private $sortingFields;
    /**
     * @var array
     */
    private $sortingTypes;
    /**
     * @var FiltersForm
     */
    private $form;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->colors)) {
                throw new ErrorException($this->emptyError('colors'));
            }
            if (empty($this->sizes)) {
                throw new ErrorException($this->emptyError('sizes'));
            }
            if (empty($this->brands)) {
                throw new ErrorException($this->emptyError('brands'));
            }
            if (empty($this->sortingFields)) {
                throw new ErrorException($this->emptyError('sortingFields'));
            }
            if (empty($this->sortingTypes)) {
                throw new ErrorException($this->emptyError('sortingTypes'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Filters');
            $renderArray['formModel'] = $this->form;
            $renderArray['sortingFields'] = $this->sortingFields;
            $renderArray['sortingTypes'] = $this->sortingTypes;
            $renderArray['colors'] = $this->colors;
            $renderArray['sizes'] = $this->sizes;
            $renderArray['brands'] = $this->brands;
            
            $renderArray['formIdApply'] = 'products-filters-form';
            $renderArray['formActionApply'] = Url::to(['/filters/set']);
            $renderArray['buttonApply'] = \Yii::t('base', 'Apply');
            
            $renderArray['formIdClean'] = 'products-filters-clean';
            $renderArray['formActionClean'] = Url::to(['/filters/unset']);
            $renderArray['buttonClean'] = \Yii::t('base', 'Clean');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству FiltersWidget::colors
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
     * Присваивает array свойству FiltersWidget::sizes
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
     * Присваивает array свойству FiltersWidget::brands
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
     * Присваивает array свойству FiltersWidget::sortingFields
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
     * Присваивает array свойству FiltersWidget::sortingTypes
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
     * Присваивает FiltersForm свойству FiltersWidget::form
     * @param FiltersForm $form
     */
    public function setForm(FiltersForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
