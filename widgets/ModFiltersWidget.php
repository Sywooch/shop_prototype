<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

/**
 * Формирует HTML строку с формой, представляющей фильтры товаров
 */
class ModFiltersWidget extends AbstractBaseWidget
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
     * @var array
     */
    private $sortingFields;
    /**
     * @var ModFiltersWidget
     */
    private $form;
    /**
     * @var string имя HTML шаблона
     */
    private $template;
    
    public function run()
    {
        try {
            if (empty($this->colors)) {
                throw new ErrorException($this->emptyError('colors'));
            }
            if (empty($this->sizes)) {
                throw new ErrorException($this->emptyError('sizes'));
            }
            if (empty($this->sortingFields)) {
                throw new ErrorException($this->emptyError('sortingFields'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['sortingFields'] = $this->sortingFields;
            $renderArray['colors'] = $this->colors;
            $renderArray['colorsArray'] = ArrayHelper::map($this->colors, 'id', 'color');
            $renderArray['sizes'] = $this->sizes;
            $renderArray['url'] = Url::current();
            
            $renderArray['modelForm'] = $this->form;
            
            $renderArray['formIdApply'] = 'products-filters-form';
            $renderArray['formActionApply'] = Url::to(['/filters/set']);
            
            $renderArray['formIdClean'] = 'products-filters-clean';
            $renderArray['formActionClean'] = Url::to(['/filters/unset']);
            
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
     * Присваивает значение ModFiltersWidget::colors
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
     * Присваивает значение ModFiltersWidget::sizes
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
     * Присваивает значение ModFiltersWidget::sortingFields
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
     * Присваивает значение ModFiltersWidget::form
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
     * Присваивает значение ModFiltersWidget::template
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
