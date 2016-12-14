<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model,
    Widget};
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с формой выбора валюты
 */
class CurrencyWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var array
     */
    public $currency;
    /**
     * @var object Model, получает данные из формы
     */
    private $form;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            /*if (empty($this->currencyCollection)) {
                throw new ErrorException($this->emptyError('currencyCollection'));
            }*/
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            //$this->currencyCollection->sort('code');
            //$currencyCollection = $this->currencyCollection->map('id', 'code');
            
            return $this->render($this->view, ['formModel'=>$this->form, 'currencyList'=>$this->currency]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству CurrencyWidget::currencyCollection
     * @param object $collection CollectionInterface
     */
    /*public function setCurrencyCollection(CollectionInterface $collection)
    {
        try {
            $this->currencyCollection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }*/
    
    /**
     * Присваивает Model свойству CurrencyWidget::form
     * @param object $model Model
     */
    public function setForm(Model $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
