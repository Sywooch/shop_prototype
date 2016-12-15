<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use app\widgets\AbstractBaseWidget;
use app\forms\ChangeCurrencyForm;

/**
 * Формирует HTML строку с формой выбора валюты
 */
class CurrencyWidget extends AbstractBaseWidget
{
    /**
     * @var array CurrencyModel
     */
    private $currency;
    /**
     * @var ChangeCurrencyForm, получает данные из формы
     */
    private $form;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Currency');
            $renderArray['formModel'] = $this->form;
            $renderArray['currencyList'] = $this->currency;
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array CurrencyWidget::currency
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
     * Присваивает ChangeCurrencyForm свойству CurrencyWidget::form
     * @param ChangeCurrencyForm $form
     */
    public function setForm(ChangeCurrencyForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
