<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;
use app\models\CurrencyInterface;

/**
 * Формирует HTML строку с данными
 */
class AdminPaymentDataWidget extends AbstractBaseWidget
{
    /**
     * @var array Model
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
            
            $renderArray['id'] = $this->payment->id;
            $renderArray['name'] = $this->payment->name;
            $renderArray['description'] = $this->payment->description;
            $renderArray['active'] = $this->payment->active ? \Yii::t('base', 'Active') : \Yii::t('base', 'Not active');
            
            $renderArray['modelForm'] = $this->form;
            
            $renderArray['formIdChange'] = sprintf('admin-payment-get-form-%d', $this->payment->id);
            $renderArray['formActionChange'] = Url::to(['/admin/payment-form']);
            $renderArray['buttonChange'] = \Yii::t('base', 'Change');
            
            $renderArray['formIdDelete'] = sprintf('admin-payment-delete-form-%d', $this->payment->id);
            $renderArray['formActionDelete'] = Url::to(['/admin/payment-delete']);
            $renderArray['buttonDelete'] = \Yii::t('base', 'Delete');
            
            $renderArray['formSettings']['ajaxValidation'] = false;
            $renderArray['formSettings']['validateOnSubmit'] = false;
            $renderArray['formSettings']['validateOnChange'] = false;
            $renderArray['formSettings']['validateOnBlur'] = false;
            $renderArray['formSettings']['validateOnType'] = false;
            
            $renderArray['nameHeader'] = \Yii::t('base', 'Name');
            $renderArray['descriptionHeader'] = \Yii::t('base', 'Description');
            $renderArray['activeHeader'] = \Yii::t('base', 'Active');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminPaymentDataWidget::payment
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
     * Присваивает значение AdminPaymentDataWidget::form
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
     * Присваивает значение AdminPaymentDataWidget::template
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
