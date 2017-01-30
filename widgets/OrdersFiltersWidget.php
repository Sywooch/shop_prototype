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
            
            //$renderArray['statuses'] = $this->statuses;
            $renderArray['sortingTypes'] = $this->sortingTypes;
            
            //$renderArray['statusLabel'] = \Yii::t('base', 'Status');
            $renderArray['sortingTypeLabel'] = \Yii::t('base', 'Sorting by date');
            
            $renderArray['calendarDate'] = \Yii::$app->formatter->asDate(DateHelper::getToday00());
            $renderArray['calendarHref'] = Url::to(['/calendar/get']);
            $renderArray['calendarTimestamp'] = DateHelper::getToday00();
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formIdApply'] = 'admin-orders-filters-form';
            $renderArray['formActionApply'] = Url::to(['/filters/orders-set']);
            $renderArray['buttonApply'] = \Yii::t('base', 'Apply');
            
            $renderArray['formIdClean'] = 'admin-orders-filters-clean';
            $renderArray['formActionClean'] = Url::to(['/filters/orders-unset']);
            $renderArray['buttonClean'] = \Yii::t('base', 'Clean');
            
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
