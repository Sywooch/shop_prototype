<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

/**
 * Формирует HTML строку с формой редактирования данных товара
 */
class AdminPaymentFormWidget extends AbstractBaseWidget
{
    /**
     * @var Model
     */
    private $payment;
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
            if (empty($this->payment)) {
                throw new ErrorException($this->emptyError('payment'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['modelForm'] = $this->form;
            
            $renderArray['id'] = $this->payment->id;
            $renderArray['name'] = $this->payment->name;
            $renderArray['description'] = $this->payment->description;
            $renderArray['active'] = !empty($this->payment->active) ? true : false;
            
            $renderArray['formId'] = sprintf('admin-payment-edit-form-%d', $this->payment->id);
            
            $renderArray['formAction'] = Url::to(['/admin/payment-change']);
            $renderArray['button'] = \Yii::t('base', 'Save');
            $renderArray['buttonCancel'] = \Yii::t('base', 'Cancel');
            
            $renderArray['cols'] = 20;
            $renderArray['rows'] = 5;
            
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
     * Присваивает значение AdminPaymentFormWidget::payment
     * @param Model $payment
     */
    public function setPayment(Model $payment)
    {
        try {
            $this->payment = $payment;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminPaymentFormWidget::form
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
     * Присваивает значение AdminPaymentFormWidget::template
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
