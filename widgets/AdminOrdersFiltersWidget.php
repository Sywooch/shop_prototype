<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\AdminOrdersFiltersForm;

/**
 * Формирует HTML строку с формой, представляющей фильтры товаров
 */
class AdminOrdersFiltersWidget extends AbstractBaseWidget
{
    /**
     * @var array варианты статусов заказа
     */
    private $statuses;
    /**
     * @var array
     */
    private $sortingTypes;
    /**
     * @var AdminOrdersFiltersForm
     */
    private $form;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->statuses)) {
                throw new ErrorException($this->emptyError('statuses'));
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
            
             $renderArray['statuses'] = $this->statuses;
            $renderArray['sortingTypes'] = $this->sortingTypes;
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formIdApply'] = 'admin-orders-filters-form';
            $renderArray['formActionApply'] = Url::to(['/filters/admin-orders-set']);
            $renderArray['buttonApply'] = \Yii::t('base', 'Apply');
            
            $renderArray['formIdClean'] = 'admin-orders-filters-clean';
            $renderArray['formActionClean'] = Url::to(['/filters/admin-orders-unset']);
            $renderArray['buttonClean'] = \Yii::t('base', 'Clean');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminOrdersFiltersWidget::statuses
     * @param array $statuses
     */
    public function setStatuses(array $statuses)
    {
        try {
            $this->statuses = $statuses;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminOrdersFiltersWidget::sortingTypes
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
     * Присваивает AdminOrdersFiltersForm свойству AdminOrdersFiltersWidget::form
     * @param AdminOrdersFiltersForm $form
     */
    public function setForm(AdminOrdersFiltersForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
