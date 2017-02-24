<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;
use app\helpers\DateHelper;

/**
 * Формирует HTML строку с формой, представляющей фильтры товаров
 */
class UsersFiltersWidget extends AbstractBaseWidget
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
     * @var UsersFiltersForm
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
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formIdApply'] = 'admin-users-filters-form';
            $renderArray['formActionApply'] = Url::to(['/filters/users-set']);
            $renderArray['buttonApply'] = \Yii::t('base', 'Apply');
            
            $renderArray['formIdClean'] = 'admin-users-filters-clean';
            $renderArray['formActionClean'] = Url::to(['/filters/users-unset']);
            $renderArray['buttonClean'] = \Yii::t('base', 'Clean');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение UsersFiltersWidget::sortingFields
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
     * Присваивает значение UsersFiltersWidget::sortingTypes
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
     * Присваивает значение UsersFiltersWidget::form
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
     * Присваивает значение UsersFiltersWidget::header
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
     * Присваивает изначение UsersFiltersWidget::template
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
