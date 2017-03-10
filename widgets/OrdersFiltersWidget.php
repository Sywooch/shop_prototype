<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\OrdersFiltersForm;
use app\helpers\DateHelper;

/**
 * Формирует HTML строку с формой, представляющей фильтры товаров
 */
class OrdersFiltersWidget extends AbstractBaseWidget
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
     * @var OrdersFiltersForm
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
            if (empty($this->statuses)) {
                throw new ErrorException($this->emptyError('statuses'));
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
            
            $renderArray['statuses'] = $this->statuses;
            $renderArray['sortingTypes'] = $this->sortingTypes;
            
            $renderArray['statusLabel'] = \Yii::t('base', 'Status');
            $renderArray['sortingTypeLabel'] = \Yii::t('base', 'Sorting by date');
            
            $renderArray['url'] = Url::current();
            $renderArray['calendarHref'] = Url::to(['/calendar/get']);
            $renderArray['calendarDateFrom'] = \Yii::$app->formatter->asDate($this->form->dateFrom ?? DateHelper::getToday00());
            $renderArray['calendarDateTo'] = \Yii::$app->formatter->asDate($this->form->dateTo ?? DateHelper::getToday00());
            $renderArray['calendarTimestampForm'] = $this->form->dateFrom ?? DateHelper::getToday00();
            $renderArray['calendarTimestampTo'] = $this->form->dateTo ?? DateHelper::getToday00();
            
            $renderArray['modelForm'] = $this->form;
            
            $renderArray['formIdApply'] = 'admin-orders-filters-form';
            $renderArray['formActionApply'] = Url::to(['/filters/orders-set']);
            $renderArray['buttonApply'] = \Yii::t('base', 'Apply');
            
            $renderArray['formIdClean'] = 'admin-orders-filters-clean';
            $renderArray['formActionClean'] = Url::to(['/filters/orders-unset']);
            $renderArray['buttonClean'] = \Yii::t('base', 'Clean');
            
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
     * Присваивает array свойству OrdersFiltersWidget::statuses
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
     * Присваивает array свойству OrdersFiltersWidget::sortingTypes
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
     * Присваивает OrdersFiltersForm свойству OrdersFiltersWidget::form
     * @param OrdersFiltersForm $form
     */
    public function setForm(OrdersFiltersForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает заголовок свойству OrdersFiltersWidget::header
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
     * Присваивает имя шаблона свойству OrdersFiltersWidget::template
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
