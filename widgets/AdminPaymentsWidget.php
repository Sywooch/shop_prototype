<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;
use app\models\CurrencyInterface;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AdminPaymentsWidget extends AbstractBaseWidget
{
    /**
     * @var array PaymentsModel
     */
    private $payments;
    /**
     * @var AbstractBaseForm
     */
    private $form;
    /**
     * @var string заголовок
     */
    private $header;
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
            if (empty($this->payments)) {
                throw new ErrorException($this->emptyError('payments'));
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
            
            $paymentsArray = [];
            foreach ($this->payments as $payment) {
                $set = [];
                $set['id'] = $payment->id;
                $set['name'] = $payment->name;
                $set['description'] = $payment->description;
                $set['active'] = $payment->active ? \Yii::t('base', 'Active') : \Yii::t('base', 'Not active');
                
                $set['formIdChange'] = sprintf('admin-payment-get-form-%d', $payment->id);
                $set['formIdDelete'] = sprintf('admin-payment-delete-form-%d', $payment->id);
                
                $paymentsArray[] = $set;
            }
            
            ArrayHelper::multisort($paymentsArray, 'name', SORT_ASC);
            $renderArray['payments'] = $paymentsArray;
            
            $renderArray['modelForm'] = $this->form;
            
            $renderArray['formActionChange'] = Url::to(['/admin/payment-form']);
            $renderArray['buttonChange'] = \Yii::t('base', 'Change');
            
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
     * Присваивает значение AdminPaymentsWidget::payments
     * @param array $payments
     */
    public function setPayments(array $payments)
    {
        try {
            $this->payments = $payments;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminPaymentsWidget::form
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
     * Присваивает значение AdminPaymentsWidget::header
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
     * Присваивает значение AdminPaymentsWidget::template
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
