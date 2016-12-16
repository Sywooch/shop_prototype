<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\Url;
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
            $renderArray['formId'] = 'set-currency-form';
            $renderArray['formAction'] = Url::to(['/currency/set']);
            $renderArray['button'] = \Yii::t('base', 'Change');
            $renderArray['formModel'] = $this->form;
            $renderArray['currency'] = $this->currency;
            
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
