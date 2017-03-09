<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AdminCurrencyWidget extends AbstractBaseWidget
{
    /**
     * @var array CurrencyModel
     */
    private $currency;
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
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
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
            
            $currencyArray = [];
            foreach ($this->currency as $currency) {
                $set = [];
                $set['id'] = $currency->id;
                $set['code'] = $currency->code;
                $set['exchange_rate'] = $currency->exchange_rate;
                if (!empty($currency->main)) {
                    $set['base'] = \Yii::t('base', 'Base currency');
                }
                $set['main'] = $currency->main;
                
                $set['formIdDelete'] = sprintf('admin-currency-delete-form-%d', $currency->id);
                $set['formIdBaseChange'] = sprintf('admin-currency-base-change-form-%d', $currency->id);
                
                $currencyArray[] = $set;
            }
            
            ArrayHelper::multisort($currencyArray, 'code', SORT_ASC);
            $renderArray['currencyArray'] = $currencyArray;
            
            $renderArray['modelForm'] = $this->form;
            
            $renderArray['formActionDelete'] = Url::to(['/admin/currency-delete']);
            $renderArray['formActionBaseChange'] = Url::to(['/admin/currency-base-change']);
            $renderArray['button'] = \Yii::t('base', 'Delete');
            
            $renderArray['formSettings']['ajaxValidation'] = false;
            $renderArray['formSettings']['validateOnSubmit'] = false;
            $renderArray['formSettings']['validateOnChange'] = false;
            $renderArray['formSettings']['validateOnBlur'] = false;
            $renderArray['formSettings']['validateOnType'] = false;
            
            $renderArray['codeHeader'] = \Yii::t('base', 'Code');
            $renderArray['exchangeHeader'] = \Yii::t('base', 'Exchange rate');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminCurrencyWidget::currency
     * @param array $currency
     */
    public function setCurrency(array $currency)
    {
        try {
            $this->currency = $currency;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminCurrencyWidget::form
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
     * Присваивает значение AdminCurrencyWidget::header
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
     * Присваивает значение AdminCurrencyWidget::template
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
