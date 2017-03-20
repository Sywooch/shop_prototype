<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с формой выбора валюты
 */
class ModCurrencyWidget extends AbstractBaseWidget
{
    /**
     * @var array CurrencyModel
     */
    private $currency;
    /**
     * @var Model
     */
    private $current;
    /**
     * @var string имя HTML шаблона
     */
    private $template;
    
    public function run()
    {
        try {
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->current)) {
                throw new ErrorException($this->emptyError('current'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['currency'] = array_filter($this->currency, function($elm) {
                return ($elm->main === 0);
            });
            
            $renderArray['current'] = $this->current->code;
            $renderArray['action'] = Url::to(['/currency/set']);
            $renderArray['link'] = Url::current();
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ModCurrencyWidget::currency
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
     * Присваивает значение ModCurrencyWidget::current
     * @param Model $current
     */
    public function setCurrent(Model $current)
    {
        try {
            $this->current = $current;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ChangeCurrencyForm::template
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
